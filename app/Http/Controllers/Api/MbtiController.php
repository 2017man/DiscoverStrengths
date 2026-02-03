<?php

namespace App\Http\Controllers\Api;

use App\Models\StrengthsTestQuestion;
use App\Models\StrengthsTestQuestionOption;
use App\Models\StrengthsTestQuestionsSection;
use App\Models\StrengthsTestResultsRecord;
use App\Models\StrengthsTestAnswer;
use App\Models\StrengthsTestType;
use App\Models\StrengthsTestDimension;
use App\Models\StrengthsTestDimensionSide;
use App\Models\StrengthsSiteConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MbtiController extends Controller
{
    const TEST_TYPE = 'MBTI';

    public function __construct()
    {
        $this->isNeedLogin = false;
        parent::__construct();
    }

    /**
     * MBTI 说明（说明页）
     * GET /api/v1/mbti/intro
     */
    public function intro(Request $request)
    {
        $ctrl = new TestTypeController();
        $ctrl->isNeedLogin = false;
        return $ctrl->detail($request->merge(['code' => self::TEST_TYPE]));
    }

    /**
     * MBTI 题目列表（答题页）
     * GET /api/v1/mbti/questions
     * 返回题目列表（含分组）及分组说明 sections，前端可按 section_code 分组展示、用 section_title 作引导语。
     */
    public function questions(Request $request)
    {
        $questions = StrengthsTestQuestion::query()
            ->where('test_type', self::TEST_TYPE)
            ->orderBy('question_number')
            ->get();

        $optionRows = StrengthsTestQuestionOption::query()
            ->where('test_type', self::TEST_TYPE)
            ->get();

        $optionsByKey = $optionRows->groupBy(function ($row) {
            return $row->section_code . '_' . $row->question_number;
        });

        $dimensionMap = ['E' => 'E-I', 'I' => 'E-I', 'S' => 'S-N', 'N' => 'S-N', 'T' => 'T-F', 'F' => 'T-F', 'J' => 'J-P', 'P' => 'J-P'];

        $data = [];
        foreach ($questions as $q) {
            $key = $q->section_code . '_' . $q->question_number;
            $opts = $optionsByKey->get($key, collect());
            $options = $opts->map(function ($o) {
                return [
                    'key' => $o->option_key,
                    'text' => $o->option_text,
                    'side' => $o->dimension_side, // 每个选项对应八个面之一 E/I/S/N/T/F/J/P，与 strengths_test_question_options.dimension_side 一致
                ];
            })->values()->toArray();
            $dimension = 'E-I';
            if ($opts->isNotEmpty()) {
                $side = $opts->first()->dimension_side;
                $dimension = $dimensionMap[$side] ?? 'E-I';
            }
            $data[] = [
                'id' => $q->id,
                'question_number' => (int) $q->question_number,
                'question_text' => $q->question_text,
                'section_code' => $q->section_code ?? '',
                'dimension' => $dimension,
                'options' => $options,
            ];
        }

        $sections = StrengthsTestQuestionsSection::query()
            ->where('test_type', self::TEST_TYPE)
            ->orderBy('sort')
            ->orderBy('id')
            ->get(['section_code', 'section_title', 'sort'])
            ->map(function ($s) {
                return [
                    'section_code' => $s->section_code,
                    'section_title' => $s->section_title ?? '',
                    'sort' => (int) $s->sort,
                ];
            })
            ->values()
            ->toArray();

        return response()->json([
            'code' => 200,
            'msg' => 'success',
            'data' => $data,
            'sections' => $sections,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 提交答案并计分，写入/更新测试记录
     * POST /api/v1/mbti/submit
     * body: { "answers": [ { "question_number": 1, "option_key": "A" }, ... ] }
     */
    public function submit(Request $request)
    {
        $answers = $request->input('answers', []);
        if (!is_array($answers) || empty($answers)) {
            return $this->error('10003', '请提交答案');
        }

        $questionNumbers = array_column($answers, 'question_number');
        $questions = StrengthsTestQuestion::query()
            ->where('test_type', self::TEST_TYPE)
            ->whereIn('question_number', $questionNumbers)
            ->get()
            ->keyBy('question_number');

        $scores = ['E' => 0, 'I' => 0, 'S' => 0, 'N' => 0, 'T' => 0, 'F' => 0, 'J' => 0, 'P' => 0];
        foreach ($answers as $a) {
            $qn = (int) ($a['question_number'] ?? 0);
            $optionKey = (string) ($a['option_key'] ?? '');
            if (!$qn || $optionKey === '') {
                continue;
            }
            $q = $questions->get($qn);
            if (!$q) {
                continue;
            }
            $opt = StrengthsTestQuestionOption::query()
                ->where('test_type', self::TEST_TYPE)
                ->where('section_code', $q->section_code)
                ->where('question_number', $qn)
                ->where('option_key', $optionKey)
                ->first();
            if ($opt && isset($scores[$opt->dimension_side])) {
                $scores[$opt->dimension_side]++;
            }
        }

        // 四维取高；同分取 I, N, F, P（见数据库设计文档）
        $d1 = $scores['E'] > $scores['I'] ? 'E' : 'I';
        $d2 = $scores['S'] > $scores['N'] ? 'S' : 'N';
        $d3 = $scores['T'] > $scores['F'] ? 'T' : 'F';
        $d4 = $scores['J'] > $scores['P'] ? 'J' : 'P';
        $resultCode = $d1 . $d2 . $d3 . $d4;

        $openid = $request->input('openid') ?? $request->header('X-Openid');
        $sessionId = $request->input('session_id') ?? $request->header('X-Session-Id') ?? session()->getId();

        $record = null;
        $existing = StrengthsTestResultsRecord::query()
            ->where('test_type', self::TEST_TYPE)
            ->where(function ($q) use ($openid, $sessionId) {
                if ($openid) {
                    $q->where('openid', $openid);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->where('is_paid', 0)
            ->first();

        $answersSnapshot = json_encode($answers, JSON_UNESCAPED_UNICODE);

        $recordData = [
            'result_code' => $resultCode,
            'answers_snapshot' => $answersSnapshot,
            'e_score' => $scores['E'],
            'i_score' => $scores['I'],
            's_score' => $scores['S'],
            'n_score' => $scores['N'],
            't_score' => $scores['T'],
            'f_score' => $scores['F'],
            'j_score' => $scores['J'],
            'p_score' => $scores['P'],
        ];

        if ($existing) {
            $existing->update($recordData);
            $record = $existing;
        } else {
            $record = StrengthsTestResultsRecord::create(array_merge($recordData, [
                'test_type' => self::TEST_TYPE,
                'openid' => $openid ?: null,
                'session_id' => $sessionId ?: null,
                'is_paid' => 0,
            ]));
        }

        $answerRow = StrengthsTestAnswer::query()
            ->where('test_type', self::TEST_TYPE)
            ->where('result_code', $resultCode)
            ->where('status', 1)
            ->first();

        $previewContent = '';
        if ($answerRow) {
            $previewContent = $answerRow->summary ?? $answerRow->traits_summary ?? $answerRow->result_name ?? '';
        }

        $data = [
            'result_id' => $record->id,
            'result_code' => $resultCode,
            'result_name' => $answerRow ? $answerRow->result_name : $resultCode,
            'preview_content' => $previewContent,
            'is_paid' => (int) $record->is_paid === 1,
        ];

        return $this->success($data);
    }

    /**
     * 报告详情（预览/完整）
     * GET /api/v1/mbti/report?result_id=xxx
     * 已付费时返回完整结构化报告，格式严格按接口文档 4.2
     */
    public function report(Request $request)
    {
        $resultId = (int) $request->get('result_id');
        if (!$resultId) {
            return $this->error('10004', '缺少 result_id');
        }

        $record = StrengthsTestResultsRecord::query()->find($resultId);
        if (!$record || $record->test_type !== self::TEST_TYPE) {
            return $this->error('10005', '测试记录不存在');
        }

        $answerRow = StrengthsTestAnswer::query()
            ->where('test_type', self::TEST_TYPE)
            ->where('result_code', $record->result_code)
            ->where('status', 1)
            ->first();

        if (!$answerRow) {
            return $this->error('10006', '报告内容不存在');
        }

        $isPaid = (int) $record->is_paid === 1;
        $fullParam = (string) $request->get('full', '');
        $forceFull = ($fullParam === '1' || $fullParam === 'true'); // 测试环境：full=1 时未付费也返回完整报告

        $summary = $answerRow->summary ?? $answerRow->result_name ?? '';
        $previewContent = $summary ?: ($answerRow->traits_summary ?? $answerRow->result_name ?? '');
        $price = '8.88';
        $testType = StrengthsTestType::query()->where('code', self::TEST_TYPE)->first();
        if ($testType) {
            $price = (string) $testType->price;
        }

        $data = [
            'result_id' => $record->id,
            'result_code' => $record->result_code,
            'result_name' => $answerRow->result_name,
            'summary' => $summary,
            'is_paid' => $isPaid,
            'price' => $price,
            'preview_content' => $previewContent,
        ];

        if ($isPaid || $forceFull) {
            $data = array_merge($data, $this->buildFullReportData($record, $answerRow));
        }

        return response()->json([
            'code' => 200,
            'msg' => 'success',
            'data' => $data,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 报告 PDF 下载
     * GET /api/v1/mbti/report/pdf?result_id=xxx
     * 已付费：返回 PDF 文件流；未付费：返回 403
     */
    public function reportPdf(Request $request)
    {
        $resultId = (int) $request->get('result_id');
        if (!$resultId) {
            return $this->error('10004', '缺少 result_id');
        }

        $record = StrengthsTestResultsRecord::query()->find($resultId);
        if (!$record || $record->test_type !== self::TEST_TYPE) {
            return $this->error('10005', '测试记录不存在');
        }

        if ((int) $record->is_paid !== 1) {
            return response()->json(['code' => 403, 'msg' => '请先付费解锁完整报告'], 403);
        }

        $answerRow = StrengthsTestAnswer::query()
            ->where('test_type', self::TEST_TYPE)
            ->where('result_code', $record->result_code)
            ->where('status', 1)
            ->first();

        if (!$answerRow) {
            return $this->error('10006', '报告内容不存在');
        }

        $fullData = $this->buildFullReportData($record, $answerRow);
        $data = [
            'result_code' => $record->result_code,
            'result_name' => $answerRow->result_name,
            'summary' => $answerRow->summary ?? $answerRow->result_name ?? '',
            'traits_summary' => $fullData['traits_summary'] ?? '',
            'traits' => $fullData['traits'] ?? '',
            'strengths' => $fullData['strengths'] ?? [],
            'weaknesses' => $fullData['weaknesses'] ?? [],
            'careers' => $fullData['careers'] ?? '',
            'suggestion' => $fullData['suggestion'] ?? '',
            'typical_figures' => is_array($fullData['typical_figures'] ?? null) ? implode('、', $fullData['typical_figures']) : ($fullData['typical_figures'] ?? ''),
        ];

        $filename = 'MBTI_' . $record->result_code . '_' . date('Ymd') . '.pdf';
        $pdf = Pdf::loadView('mbti.report-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * 构建已付费完整报告数据
     */
    protected function buildFullReportData(StrengthsTestResultsRecord $record, StrengthsTestAnswer $answerRow): array
    {
        $dimensionOrder = ['E-I', 'S-N', 'T-F', 'J-P'];
        $scoreMap = [
            'E-I' => ['left' => 'e_score', 'right' => 'i_score', 'leftCode' => 'E', 'rightCode' => 'I'],
            'S-N' => ['left' => 's_score', 'right' => 'n_score', 'leftCode' => 'S', 'rightCode' => 'N'],
            'T-F' => ['left' => 't_score', 'right' => 'f_score', 'leftCode' => 'T', 'rightCode' => 'F'],
            'J-P' => ['left' => 'j_score', 'right' => 'p_score', 'leftCode' => 'J', 'rightCode' => 'P'],
        ];

        $sideOrder = ['E', 'I', 'S', 'N', 'T', 'F', 'J', 'P'];
        $sidesRaw = StrengthsTestDimensionSide::query()
            ->where('test_type', self::TEST_TYPE)
            ->get()
            ->keyBy('side_code');
        $dimensionSides = [];
        foreach ($sideOrder as $code) {
            $s = $sidesRaw->get($code);
            if ($s) {
                $dimensionSides[] = ['side_code' => $s->side_code, 'side_name' => $s->side_name];
            }
        }

        $dimensions = StrengthsTestDimension::query()
            ->where('test_type', self::TEST_TYPE)
            ->whereIn('dimension_code', $dimensionOrder)
            ->orderByRaw("FIELD(dimension_code, 'E-I', 'S-N', 'T-F', 'J-P')")
            ->get()
            ->map(fn ($d) => ['dimension_code' => $d->dimension_code, 'dimension_scope' => $d->dimension_scope ?? ''])
            ->toArray();

        $dimensionScores = [];
        foreach ($dimensionOrder as $dimCode) {
            $cfg = $scoreMap[$dimCode];
            $leftVal = (int) ($record->{$cfg['left']} ?? 0);
            $rightVal = (int) ($record->{$cfg['right']} ?? 0);
            $total = $leftVal + $rightVal;
            if ($total > 0) {
                $leftPct = round($leftVal / $total * 100, 2);
                $rightPct = round($rightVal / $total * 100, 2);
            } else {
                $leftPct = 50;
                $rightPct = 50;
            }
            $dimensionScores[] = [
                'dimension_code' => $dimCode,
                'leftCode' => $cfg['leftCode'],
                'rightCode' => $cfg['rightCode'],
                'leftScore' => $leftPct,
                'rightScore' => $rightPct,
            ];
        }

        $sidesByDimension = StrengthsTestDimensionSide::query()
            ->where('test_type', self::TEST_TYPE)
            ->get()
            ->groupBy('dimension_code');

        $dimensionsDetail = [];
        foreach ($dimensionOrder as $dimCode) {
            $cfg = $scoreMap[$dimCode];
            $leftVal = (int) ($record->{$cfg['left']} ?? 0);
            $rightVal = (int) ($record->{$cfg['right']} ?? 0);
            $total = $leftVal + $rightVal;
            if ($total > 0) {
                $leftPct = round($leftVal / $total * 100, 1);
                $rightPct = round($rightVal / $total * 100, 1);
            } else {
                $leftPct = 50;
                $rightPct = 50;
            }

            $sides = $sidesByDimension->get($dimCode, collect());
            $leftSide = $sides->firstWhere('side_code', $cfg['leftCode']);
            $rightSide = $sides->firstWhere('side_code', $cfg['rightCode']);

            $dim = StrengthsTestDimension::query()
                ->where('test_type', self::TEST_TYPE)
                ->where('dimension_code', $dimCode)
                ->first();

            $dimensionsDetail[] = [
                'title' => $dim ? ($dim->dimension_scope ?? '') : '',
                'leftCode' => $cfg['leftCode'],
                'rightCode' => $cfg['rightCode'],
                'leftScore' => $leftPct,
                'rightScore' => $rightPct,
                'leftName' => $leftSide ? ($leftSide->side_name ?? '') : '',
                'rightName' => $rightSide ? ($rightSide->side_name ?? '') : '',
                'leftDesc' => $leftSide ? ($leftSide->overview ?? '') : '',
                'rightDesc' => $rightSide ? ($rightSide->overview ?? '') : '',
                'leftOverview' => $leftSide ? ($leftSide->overview ?? '') : '',
                'rightOverview' => $rightSide ? ($rightSide->overview ?? '') : '',
                'leftFeatures' => $leftSide ? ($leftSide->features ?? '') : '',
                'rightFeatures' => $rightSide ? ($rightSide->features ?? '') : '',
                'leftKeywords' => $leftSide ? ($leftSide->keywords ?? '') : '',
                'rightKeywords' => $rightSide ? ($rightSide->keywords ?? '') : '',
                'leftExpression' => $leftSide ? ($leftSide->expression ?? '') : '',
                'rightExpression' => $rightSide ? ($rightSide->expression ?? '') : '',
                'leftMantra' => $leftSide ? ($leftSide->mantra ?? '') : '',
                'rightMantra' => $rightSide ? ($rightSide->mantra ?? '') : '',
            ];
        }

        $strengths = $this->parseTextToArray($answerRow->strengths ?? '', 'semicolon');
        $weaknesses = $this->parseTextToArray($answerRow->weaknesses ?? '', 'semicolon');
        $typicalFigures = $answerRow->typical_figures ?? '';
        $typicalFiguresArr = $this->parseTextToArray($typicalFigures);

        $traits = $this->formatParagraphText($answerRow->traits ?? '');
        $careers = $this->formatParagraphText($answerRow->careers ?? '');

        $result = [
            'dimension_scores' => $dimensionScores,
            'dimension_sides' => $dimensionSides,
            'dimensions' => $dimensions,
            'dimensions_detail' => $dimensionsDetail,
            'traits_summary' => $answerRow->traits_summary ?? '',
            'traits' => $traits ?: ($answerRow->traits ?? ''),
            'style_intro' => $answerRow->traits_summary ?? '',
            'typical_figures' => $typicalFiguresArr ?: $typicalFigures,
            'typical_characters' => $typicalFiguresArr ?: $typicalFigures,
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'careers' => $careers ?: ($answerRow->careers ?? ''),
            'overview' => $answerRow->summary ?? $answerRow->traits ?? '',
            'suggestion' => $answerRow->suggestion ?? '',
        ];

        $siteConfig = StrengthsSiteConfig::query()->first();
        if ($siteConfig) {
            if ($siteConfig->qrcode_wechat) {
                $result['qrcode_wechat'] = (string) $siteConfig->qrcode_wechat;
            }
            if ($siteConfig->qrcode_community) {
                $result['qrcode_community'] = (string) $siteConfig->qrcode_community;
            }
        }

        return $result;
    }

    /**
     * 将文本解析为数组
     * @param string|null $text 待解析文本
     * @param string $mode semicolon=优势/劣势格式（按；分隔，项内可含、，）；default=典型人物等（按换行、顿号、逗号分隔）
     */
    protected function parseTextToArray(?string $text, string $mode = 'default'): array
    {
        if (empty(trim((string) $text))) {
            return [];
        }
        $text = trim((string) $text);
        if ($mode === 'semicolon') {
            // 优势/劣势格式：① xxx；② xxx；③ xxx —— 仅按分号分隔，保留项内的顿号、逗号
            $items = preg_split('/[；;]\s*/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        } else {
            // 典型人物等：张三、李四、王五 —— 按换行、顿号、逗号分隔
            $items = preg_split('/[\n\r、,，]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        }
        return array_values(array_map('trim', array_filter($items)));
    }

    /**
     * 将分号分隔的段落文本格式化为换行分隔的字符串（用于 traits、careers 等）
     * 格式：段落1；段落2；段落3 → 段落1\n\n段落2\n\n段落3，便于 pre-wrap 显示
     */
    protected function formatParagraphText(?string $text): string
    {
        if (empty(trim((string) $text))) {
            return '';
        }
        $paragraphs = $this->parseTextToArray($text, 'semicolon');
        return implode("\n\n", $paragraphs);
    }
}
