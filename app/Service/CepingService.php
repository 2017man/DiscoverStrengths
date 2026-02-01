<?php


namespace App\Service;


use App\Models\CepingConfig;
use App\Models\CepingCount;
use App\Models\CepingCountBrief;
use App\Models\CepingData;
use App\Models\CepingMems;
use App\Models\CepingUnit;
use Illuminate\Support\Facades\DB;

class CepingService extends Service
{

    public $effectCodeNums;

    /**
     * 优化后的统计方法 - 使用批量查询和原生SQL
     */
    public function count()
    {
        $start = time();

        // 1. 预加载所有需要的基础数据
        $memUnitCodes = CepingMems::query()->whereNotNull('code')->distinct()->pluck('code')->toArray();
        if (empty($memUnitCodes)) {
            print('没有找到单位编码，退出统计');
            return;
        }

        $units = CepingUnit::query()->whereIn('code', $memUnitCodes)->get()->keyBy('code')->toArray();

        // 预加载所有人员数据，按code分组
        $allMems = CepingMems::query()
            ->whereNotNull('code')
            ->whereIn('code', $memUnitCodes)
            ->get()
            ->groupBy('code')
            ->map(function ($items) {
                return $items->toArray();
            })
            ->toArray();

        // 2. 批量获取所有单位的测评人数统计
        $codeNumsCount = CepingData::query()
            ->whereIn('code', $memUnitCodes)
            ->selectRaw('code, COUNT(DISTINCT code_num) as code_nums')
            ->groupBy('code')
            ->pluck('code_nums', 'code')
            ->toArray();

        // 3. 批量获取有效票数（按单位和分类）
        $effectiveCodeNumsMap = $this->getEffectiveCodeNumsBatch($memUnitCodes, $units);

        // 4. 批量获取所有统计数据进行聚合
        $allInsertData = [];
        $createdAt = date('Y-m-d H:i:s');

        foreach ($units as $unit) {
            $code = $unit['code'];
            $codeNums = $codeNumsCount[$code] ?? 0;
            $mems = $allMems[$code] ?? [];

            // 获取该单位的有效票数
            $unitEffectiveCodeNums = $effectiveCodeNumsMap[$code] ?? [];
            $effectCodeNumsCount = count($unitEffectiveCodeNums);

            // 处理班子和干部数据
            foreach (CepingConfig::dataTypes as $dataType) {
                foreach (CepingConfig::categorys as $category) {
                    if ($category == '民主测评') {
                        $data = $this->processMzcpData(
                            $unit,
                            $mems,
                            $dataType,
                            $category,
                            $codeNums,
                            $effectCodeNumsCount,
                            $unitEffectiveCodeNums,
                            $createdAt
                        );
                        $allInsertData = array_merge($allInsertData, $data);
                    } elseif (in_array($category, ['政治素质考察测评_正向测评', '政治素质考察测评_反向测评'])) {
                        $data = $this->processZzszData(
                            $unit,
                            $mems,
                            $dataType,
                            $category,
                            $codeNums,
                            $effectCodeNumsCount,
                            $unitEffectiveCodeNums,
                            $createdAt
                        );
                        $allInsertData = array_merge($allInsertData, $data);
                    }
                }
            }
        }

        // 5. 批量插入数据
        if (!empty($allInsertData)) {
            // 先清空旧数据（如果需要）
            // DB::table('ceping_count')->truncate();

            // 分批插入，避免单次插入数据过大
            foreach (array_chunk($allInsertData, 1000) as $chunk) {
                CepingCount::query()->insert($chunk);
            }
        }

        $end = time();
        print('count() 完成，共计耗时：' . ($end - $start) . '秒，插入记录数：' . count($allInsertData));
    }

    /**
     * 批量获取有效票数
     */
    private function getEffectiveCodeNumsBatch($memUnitCodes, $units)
    {
        $result = [];

        // 按单位批量查询民主测评的有效票数
        $mzcpData = CepingData::query()
            ->whereIn('code', $memUnitCodes)
            ->where('first_classify', '民主测评')
            ->whereIn('second_classify', ['县管领导班子民主测评', '县管领导干部民主测评'])
            ->select('code', 'code_num')
            ->distinct()
            ->get()
            ->groupBy('code')
            ->map(function ($items) {
                return $items->pluck('code_num')->unique()->toArray();
            })
            ->toArray();

        // 处理每个单位的有效票数交集
        foreach ($units as $code => $unit) {
            $effectiveNums = $mzcpData[$code] ?? [];

            // 处理政治素质考察测评的有效票数（仅班子）
            $zzszData = CepingData::query()
                ->where('code', $code)
                ->where('first_classify', '政治素质考察')
                ->whereIn('second_classify', [
                    '领导班子政治素质考察专项测评(正向)',
                    '领导班子政治素质考察专项测评(反向)'
                ])
                ->where('name', $unit['unit'] . '领导班子')
                ->select('code_num')
                ->distinct()
                ->pluck('code_num')
                ->toArray();

            if (!empty($zzszData)) {
                $effectiveNums = array_intersect($effectiveNums, $zzszData);
            }

            $result[$code] = $effectiveNums;
        }

        return $result;
    }

    /**
     * 处理民主测评数据
     */
    private function processMzcpData($unit, $mems, $dataType, $category, $codeNums, $effectCodeNumsCount, $effectiveCodeNums, $createdAt)
    {
        $inserts = [];
        $code = $unit['code'];

        if ($dataType == '班子') {
            // 批量查询班子民主测评数据
            $contentNums = $this->getContentNumsBatch(
                $code,
                ['first_classify' => '民主测评', 'second_classify' => '县管领导班子民主测评'],
                $effectiveCodeNums
            );

            foreach (CepingConfig::selectBzMzcp as $content) {
                $inserts[] = [
                    'data_type'           => $dataType,
                    'category'            => $category,
                    'created_at'          => $createdAt,
                    'unit'                => $unit['unit'],
                    'mem_id'              => 0,
                    'name'                => $unit['unit'] . '领导班子',
                    'code'                => $code,
                    'job'                 => '',
                    'job_type'            => '',
                    'code_nums'           => $codeNums,
                    'effective_code_nums' => $effectCodeNumsCount,
                    'classify'            => '班子民主测评',
                    'classify_index'      => '-1',
                    'content'             => $content,
                    'content_nums'        => $contentNums[$content] ?? 0,
                ];
            }
        } elseif ($dataType == '干部') {
            // 批量查询所有干部的民主测评数据
            $memIds = array_column($mems, 'id');
            if (!empty($memIds)) {
                $contentNumsMap = $this->getContentNumsBatchForMems(
                    $code,
                    ['first_classify' => '民主测评', 'second_classify' => '县管领导干部民主测评'],
                    $memIds,
                    $effectiveCodeNums
                );

                foreach ($mems as $mem) {
                    $contentNums = $contentNumsMap[$mem['id']] ?? [];
                    foreach (CepingConfig::selectGbMzcp as $content) {
                        $inserts[] = [
                            'data_type'           => $dataType,
                            'category'            => $category,
                            'created_at'          => $createdAt,
                            'unit'                => $mem['unit'],
                            'mem_id'              => $mem['id'],
                            'name'                => $mem['name'],
                            'code'                => $code,
                            'job'                 => $mem['job'],
                            'job_type'            => $mem['job_type'],
                            'code_nums'           => $codeNums,
                            'effective_code_nums' => $effectCodeNumsCount,
                            'classify'            => '干部民主测评',
                            'classify_index'      => '-1',
                            'content'             => $content,
                            'content_nums'        => $contentNums[$content] ?? 0,
                        ];
                    }
                }
            }
        }

        return $inserts;
    }

    /**
     * 处理政治素质考察测评数据
     */
    private function processZzszData($unit, $mems, $dataType, $category, $codeNums, $effectCodeNumsCount, $effectiveCodeNums, $createdAt)
    {
        $inserts = [];
        $code = $unit['code'];

        if ($dataType == '班子') {
            $classifys = $category == '政治素质考察测评_正向测评' ? CepingConfig::zzszBznext : CepingConfig::zzszBzPre;
            $contentArr = $category == '政治素质考察测评_正向测评' ? CepingConfig::selectZzszNext : CepingConfig::selectZzszPre;
            $secondClassify = $category == '政治素质考察测评_正向测评'
                ? '领导班子政治素质考察专项测评(正向)'
                : '领导班子政治素质考察专项测评(反向)';

            // 批量查询所有classify的数据
            $classifyNames = array_column($classifys, 'name');
            $contentNumsMap = $this->getContentNumsBatchForClassifys(
                $code,
                [
                    'first_classify'  => '政治素质考察',
                    'second_classify' => $secondClassify,
                    'name'            => $unit['unit'] . '领导班子'
                ],
                $classifyNames,
                $effectiveCodeNums
            );

            foreach ($classifys as $index => $classify) {
                $contentNums = $contentNumsMap[$classify['name']] ?? [];
                foreach ($contentArr as $content) {
                    $inserts[] = [
                        'data_type'           => $dataType,
                        'category'            => $category,
                        'created_at'          => $createdAt,
                        'unit'                => $unit['unit'],
                        'mem_id'              => 0,
                        'name'                => $unit['unit'] . '领导班子',
                        'code'                => $code,
                        'job'                 => '',
                        'job_type'            => '',
                        'code_nums'           => $codeNums,
                        'effective_code_nums' => $effectCodeNumsCount,
                        'classify'            => $classify['name'],
                        'classify_index'      => $index,
                        'content'             => $content,
                        'content_nums'        => $contentNums[$content] ?? 0,
                    ];
                }
            }
        } elseif ($dataType == '干部') {
            $contentArr = $category == '政治素质考察测评_正向测评' ? CepingConfig::selectZzszNext : CepingConfig::selectZzszPre;

            foreach ($mems as $mem) {
                if ($category == '政治素质考察测评_正向测评') {
                    $classifys = CepingConfig::zzszGbNext;
                    $secondClassify = '领导干部政治素质考察正向测评';
                } else {
                    if ($mem['job_type'] == '正职') {
                        $classifys = CepingConfig::zzszGbPre;
                        $secondClassify = '领导干部政治素质考察反向测评（正职）';
                    } else {
                        $classifys = CepingConfig::zzsz2GbPre;
                        $secondClassify = '领导干部政治素质考察反向测评（副职）';
                    }
                }

                // 批量查询该干部的所有classify数据
                $classifyNames = array_column($classifys, 'name');
                $contentNumsMap = $this->getContentNumsBatchForClassifys(
                    $code,
                    [
                        'first_classify'  => '政治素质考察',
                        'second_classify' => $secondClassify,
                        'mem_id'          => $mem['id']
                    ],
                    $classifyNames,
                    $effectiveCodeNums
                );

                foreach ($classifys as $index => $classify) {
                    $contentNums = $contentNumsMap[$classify['name']] ?? [];
                    foreach ($contentArr as $content) {
                        $inserts[] = [
                            'data_type'           => $dataType,
                            'category'            => $category,
                            'created_at'          => $createdAt,
                            'unit'                => $mem['unit'],
                            'mem_id'              => $mem['id'],
                            'name'                => $mem['name'],
                            'code'                => $code,
                            'job'                 => $mem['job'],
                            'job_type'            => $mem['job_type'],
                            'code_nums'           => $codeNums,
                            'effective_code_nums' => $effectCodeNumsCount,
                            'classify'            => $classify['name'],
                            'classify_index'      => $index,
                            'content'             => $content,
                            'content_nums'        => $contentNums[$content] ?? 0,
                        ];
                    }
                }
            }
        }

        return $inserts;
    }

    /**
     * 批量获取内容统计（班子用）
     */
    private function getContentNumsBatch($code, $conditions, $effectiveCodeNums)
    {
        if (empty($effectiveCodeNums)) {
            return [];
        }

        $query = CepingData::query()
            ->where('code', $code)
            ->whereIn('code_num', $effectiveCodeNums);

        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }

        return $query->selectRaw('content, COUNT(DISTINCT code_num) as nums')
            ->groupBy('content')
            ->pluck('nums', 'content')
            ->toArray();
    }

    /**
     * 批量获取多个干部的内容统计
     */
    private function getContentNumsBatchForMems($code, $conditions, $memIds, $effectiveCodeNums)
    {
        if (empty($effectiveCodeNums) || empty($memIds)) {
            return [];
        }

        $query = CepingData::query()
            ->where('code', $code)
            ->whereIn('code_num', $effectiveCodeNums)
            ->whereIn('mem_id', $memIds);

        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }

        $results = $query->selectRaw('mem_id, content, COUNT(DISTINCT code_num) as nums')
            ->groupBy('mem_id', 'content')
            ->get();

        // 按mem_id分组
        $resultMap = [];
        foreach ($results as $result) {
            $resultMap[$result->mem_id][$result->content] = $result->nums;
        }

        return $resultMap;
    }

    /**
     * 批量获取多个classify的内容统计
     */
    private function getContentNumsBatchForClassifys($code, $conditions, $classifyNames, $effectiveCodeNums)
    {
        if (empty($effectiveCodeNums) || empty($classifyNames)) {
            return [];
        }

        $query = CepingData::query()
            ->where('code', $code)
            ->whereIn('code_num', $effectiveCodeNums)
            ->whereIn('third_classify', $classifyNames);

        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }

        $results = $query->selectRaw('third_classify, content, COUNT(DISTINCT code_num) as nums')
            ->groupBy('third_classify', 'content')
            ->get();

        // 按third_classify分组
        $resultMap = [];
        foreach ($results as $result) {
            $resultMap[$result->third_classify][$result->content] = $result->nums;
        }

        return $resultMap;
    }

    /**
     * 优化后的简要统计方法
     */
    public function brief()
    {
        $start = time();

        // 1. 预加载所有需要的基础数据
        $memUnitCodes = CepingMems::query()->whereNotNull('code')->distinct()->pluck('code')->toArray();
        if (empty($memUnitCodes)) {
            print('没有找到单位编码，退出统计');
            return;
        }

        $units = CepingUnit::query()->whereIn('code', $memUnitCodes)->get()->keyBy('code')->toArray();

        // 预加载所有人员数据，按code分组
        $allMems = CepingMems::query()
            ->whereNotNull('code')
            ->whereIn('code', $memUnitCodes)
            ->get()
            ->groupBy('code')
            ->map(function ($items) {
                return $items->toArray();
            })
            ->toArray();

        // 2. 批量获取所有单位的统计信息
        $codeNumsCount = CepingCount::query()
            ->whereIn('code', $memUnitCodes)
            ->selectRaw('code, MAX(code_nums) as code_nums, MAX(effective_code_nums) as effective_code_nums')
            ->groupBy('code')
            ->get()
            ->keyBy('code')
            ->toArray();

        // 3. 批量获取所有需要的数据
        $allInsertData = [];
        $createdAt = date('Y-m-d H:i:s');

        foreach ($units as $unit) {
            $code = $unit['code'];
            $codeNums = $codeNumsCount[$code]['code_nums'] ?? 0;
            $effectCodeNumsCount = $codeNumsCount[$code]['effective_code_nums'] ?? 0;
            $mems = $allMems[$code] ?? [];

            foreach (CepingConfig::dataTypes as $dataType) {
                foreach (CepingConfig::categorys as $category) {
                    if ($category == '民主测评') {
                        $data = $this->processBriefMzcpData(
                            $unit,
                            $mems,
                            $dataType,
                            $category,
                            $codeNums,
                            $effectCodeNumsCount,
                            $code,
                            $createdAt
                        );
                        $allInsertData = array_merge($allInsertData, $data);
                    } elseif (in_array($category, ['政治素质考察测评_正向测评', '政治素质考察测评_反向测评'])) {
                        $data = $this->processBriefZzszData(
                            $unit,
                            $mems,
                            $dataType,
                            $category,
                            $codeNums,
                            $effectCodeNumsCount,
                            $code,
                            $createdAt
                        );
                        $allInsertData = array_merge($allInsertData, $data);
                    }
                }
            }
        }

        // 4. 批量插入数据
        if (!empty($allInsertData)) {
            foreach (array_chunk($allInsertData, 1000) as $chunk) {
                CepingCountBrief::query()->insert($chunk);
            }
        }

        $end = time();
        print('brief() 完成，共计耗时：' . ($end - $start) . '秒，插入记录数：' . count($allInsertData));
    }

    /**
     * 处理简要统计的民主测评数据
     */
    private function processBriefMzcpData($unit, $mems, $dataType, $category, $codeNums, $effectCodeNumsCount, $code, $createdAt)
    {
        $inserts = [];

        if ($dataType == '班子') {
            // 批量查询
            $contentNums = CepingCount::query()
                ->where('code', $code)
                ->where('data_type', $dataType)
                ->where('category', $category)
                ->where('name', $unit['unit'] . '领导班子')
                ->where('classify', '班子民主测评')
                ->where('classify_index', '-1')
                ->selectRaw('content, SUM(content_nums) as nums')
                ->groupBy('content')
                ->pluck('nums', 'content')
                ->toArray();

            foreach (CepingConfig::selectBzMzcp as $content) {
                $inserts[] = [
                    'data_type'           => $dataType,
                    'category'            => $category,
                    'created_at'          => $createdAt,
                    'unit'                => $unit['unit'],
                    'mem_id'              => 0,
                    'name'                => $unit['unit'] . '领导班子',
                    'code'                => $code,
                    'job'                 => '',
                    'job_type'            => '',
                    'code_nums'           => $codeNums,
                    'effective_code_nums' => $effectCodeNumsCount,
                    'content'             => $content,
                    'content_nums'        => $contentNums[$content] ?? 0,
                ];
            }
        } elseif ($dataType == '干部') {
            // 批量查询所有干部的数据
            $memIds = array_column($mems, 'id');
            if (!empty($memIds)) {
                $contentNumsMap = CepingCount::query()
                    ->where('code', $code)
                    ->where('data_type', $dataType)
                    ->where('category', $category)
                    ->whereIn('mem_id', $memIds)
                    ->where('classify', '干部民主测评')
                    ->where('classify_index', '-1')
                    ->selectRaw('mem_id, content, SUM(content_nums) as nums')
                    ->groupBy('mem_id', 'content')
                    ->get()
                    ->groupBy('mem_id')
                    ->map(function ($items) {
                        return $items->pluck('nums', 'content')->toArray();
                    })
                    ->toArray();

                foreach ($mems as $mem) {
                    $contentNums = $contentNumsMap[$mem['id']] ?? [];
                    foreach (CepingConfig::selectGbMzcp as $content) {
                        $inserts[] = [
                            'data_type'           => $dataType,
                            'category'            => $category,
                            'created_at'          => $createdAt,
                            'unit'                => $mem['unit'],
                            'mem_id'              => $mem['id'],
                            'name'                => $mem['name'],
                            'code'                => $code,
                            'job'                 => $mem['job'],
                            'job_type'            => $mem['job_type'],
                            'code_nums'           => $codeNums,
                            'effective_code_nums' => $effectCodeNumsCount,
                            'content'             => $content,
                            'content_nums'        => $contentNums[$content] ?? 0,
                        ];
                    }
                }
            }
        }

        return $inserts;
    }

    /**
     * 处理简要统计的政治素质考察测评数据
     */
    private function processBriefZzszData($unit, $mems, $dataType, $category, $codeNums, $effectCodeNumsCount, $code, $createdAt)
    {
        $inserts = [];
        $contentArr = $category == '政治素质考察测评_正向测评' ? CepingConfig::selectZzszNext : CepingConfig::selectZzszPre;

        if ($dataType == '班子') {
            // 批量查询所有内容
            $contentNums = CepingCount::query()
                ->where('code', $code)
                ->where('data_type', $dataType)
                ->where('category', $category)
                ->where('name', $unit['unit'] . '领导班子')
                ->selectRaw('content, SUM(content_nums) as nums')
                ->groupBy('content')
                ->pluck('nums', 'content')
                ->toArray();

            foreach ($contentArr as $content) {
                $inserts[] = [
                    'data_type'           => $dataType,
                    'category'            => $category,
                    'created_at'          => $createdAt,
                    'unit'                => $unit['unit'],
                    'mem_id'              => 0,
                    'name'                => $unit['unit'] . '领导班子',
                    'code'                => $code,
                    'job'                 => '',
                    'job_type'            => '',
                    'code_nums'           => $codeNums,
                    'effective_code_nums' => $effectCodeNumsCount,
                    'content'             => $content,
                    'content_nums'        => $contentNums[$content] ?? 0,
                ];
            }
        } elseif ($dataType == '干部') {
            // 批量查询所有干部的数据
            $memIds = array_column($mems, 'id');
            if (!empty($memIds)) {
                $contentNumsMap = CepingCount::query()
                    ->where('code', $code)
                    ->where('data_type', $dataType)
                    ->where('category', $category)
                    ->whereIn('mem_id', $memIds)
                    ->selectRaw('mem_id, content, SUM(content_nums) as nums')
                    ->groupBy('mem_id', 'content')
                    ->get()
                    ->groupBy('mem_id')
                    ->map(function ($items) {
                        return $items->pluck('nums', 'content')->toArray();
                    })
                    ->toArray();

                foreach ($mems as $mem) {
                    $contentNums = $contentNumsMap[$mem['id']] ?? [];
                    foreach ($contentArr as $content) {
                        $inserts[] = [
                            'data_type'           => $dataType,
                            'category'            => $category,
                            'created_at'          => $createdAt,
                            'unit'                => $mem['unit'],
                            'mem_id'              => $mem['id'],
                            'name'                => $mem['name'],
                            'code'                => $code,
                            'job'                 => $mem['job'],
                            'job_type'            => $mem['job_type'],
                            'code_nums'           => $codeNums,
                            'effective_code_nums' => $effectCodeNumsCount,
                            'content'             => $content,
                            'content_nums'        => $contentNums[$content] ?? 0,
                        ];
                    }
                }
            }
        }

        return $inserts;
    }

    /**
     * 民意调查统计方法
     * 统计各单位的民意调查数据
     */
    public function countMinyi()
    {
        $start = time();

        // 获取所有有民意调查数据的单位编码
        $unitCodes = \App\Models\CepingMinyiData::query()
            ->whereNotNull('code')
            ->distinct()
            ->pluck('code')
            ->toArray();

        if (empty($unitCodes)) {
            print('没有找到民意调查数据，退出统计');
            return;
        }

        // 批量获取单位信息
        $units = CepingUnit::query()
            ->whereIn('code', $unitCodes)
            ->get()
            ->keyBy('code')
            ->toArray();

        // 批量获取每个单位的参与人数（去重 code_num）
        $codeNumsCount = \App\Models\CepingMinyiData::query()
            ->whereIn('code', $unitCodes)
            ->selectRaw('code, COUNT(DISTINCT code_num) as code_nums')
            ->groupBy('code')
            ->pluck('code_nums', 'code')
            ->toArray();

        // 批量获取每个单位的统计数据（按单位、评价项目、评价结果分组）
        $statisticsData = \App\Models\CepingMinyiData::query()
            ->whereIn('code', $unitCodes)
            ->selectRaw('code, item_name, item_value, COUNT(DISTINCT code_num) as nums')
            ->groupBy('code', 'item_name', 'item_value')
            ->get();

        // 按单位分组统计数据
        $statisticsMap = [];
        foreach ($statisticsData as $item) {
            $statisticsMap[$item->code][$item->item_name][$item->item_value] = $item->nums;
        }

        $end = time();
        print('countMinyi() 完成，共计耗时：' . ($end - $start) . '秒，统计单位数：' . count($unitCodes));

        // 民意调查统计不需要保存到统计表，直接用于导出
        // 统计数据已通过导出类直接查询原始数据生成
    }
}
