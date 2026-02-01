<?php

namespace App\Http\Controllers\Api;

use App\Models\StrengthsTestQuestion;
use App\Models\StrengthsTestQuestionOption;
use App\Models\StrengthsTestResultsRecord;
use App\Models\StrengthsTestAnswer;
use App\Models\StrengthsTestType;
use Illuminate\Http\Request;

class MbtiController extends Controller
{
    const TEST_TYPE = 'MBTI';

    public function __construct()
    {
        parent::__construct();
        $this->isNeedLogin = false;
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

        $dimensionMap = ['E' => 'EI', 'I' => 'EI', 'S' => 'SN', 'N' => 'SN', 'T' => 'TF', 'F' => 'TF', 'J' => 'JP', 'P' => 'JP'];

        $data = [];
        foreach ($questions as $q) {
            $key = $q->section_code . '_' . $q->question_number;
            $opts = $optionsByKey->get($key, collect());
            $options = $opts->map(function ($o) {
                return ['key' => $o->option_key, 'text' => $o->option_text];
            })->values()->toArray();
            $dimension = 'EI';
            if ($opts->isNotEmpty()) {
                $side = $opts->first()->dimension_side;
                $dimension = $dimensionMap[$side] ?? 'EI';
            }
            $data[] = [
                'id' => $q->id,
                'question_number' => (int) $q->question_number,
                'question_text' => $q->question_text,
                'dimension' => $dimension,
                'options' => $options,
            ];
        }

        return $this->success($data);
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

        if ($existing) {
            $existing->update([
                'result_code' => $resultCode,
                'answers_snapshot' => $answersSnapshot,
            ]);
            $record = $existing;
        } else {
            $record = StrengthsTestResultsRecord::create([
                'test_type' => self::TEST_TYPE,
                'result_code' => $resultCode,
                'openid' => $openid ?: null,
                'session_id' => $sessionId ?: null,
                'answers_snapshot' => $answersSnapshot,
                'is_paid' => 0,
            ]);
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
        $previewContent = $answerRow->summary ?? $answerRow->traits_summary ?? $answerRow->result_name ?? '';
        $fullContent = null;
        $price = '8.88';
        $testType = StrengthsTestType::query()->where('code', self::TEST_TYPE)->first();
        if ($testType) {
            $price = (string) $testType->price;
        }

        if ($isPaid) {
            $fullContent = [
                'summary' => $answerRow->summary,
                'traits_summary' => $answerRow->traits_summary,
                'traits' => $answerRow->traits,
                'strengths' => $answerRow->strengths,
                'weaknesses' => $answerRow->weaknesses,
                'careers' => $answerRow->careers,
                'typical_figures' => $answerRow->typical_figures,
            ];
        }

        $data = [
            'result_id' => $record->id,
            'result_code' => $record->result_code,
            'result_name' => $answerRow->result_name,
            'preview_content' => $previewContent,
            'full_content' => $fullContent,
            'is_paid' => $isPaid,
            'price' => $price,
        ];

        return $this->success($data);
    }
}
