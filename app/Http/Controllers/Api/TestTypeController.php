<?php

namespace App\Http\Controllers\Api;

use App\Models\StrengthsTestType;
use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    public function __construct()
    {
        $this->isNeedLogin = false;
        parent::__construct();
    }

    /**
     * 测试类型列表（首页 4 卡片）
     * GET /api/v1/test-types/list
     */
    public function list(Request $request)
    {
        $list = StrengthsTestType::query()
            ->where('status', 1)
            ->orderBy('sort')
            ->orderBy('id')
            ->get(['id', 'code', 'name', 'description', 'total_questions', 'price', 'sort', 'status']);

        $data = $list->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'description' => $item->description ?? '',
                'total_questions' => (int) $item->total_questions,
                'price' => (string) $item->price,
                'sort' => (int) $item->sort,
                'status' => (int) $item->status,
                'is_available' => $item->code === 'MBTI', // v1.0 仅 MBTI 可测
            ];
        });

        return $this->success($data->toArray());
    }

    /**
     * 测试类型详情（说明页）
     * GET /api/v1/test-types/detail?code=MBTI 或 GET /api/v1/mbti/intro
     */
    public function detail(Request $request)
    {
        $code = $request->get('code', 'MBTI');
        $item = StrengthsTestType::query()->where('code', $code)->where('status', 1)->first();
        if (!$item) {
            return $this->error('10002', '测试类型不存在或已禁用');
        }

        $estimateMinutes = $item->estimate_minutes !== null ? (int) $item->estimate_minutes : null;
        if ($estimateMinutes === null || $estimateMinutes < 1) {
            $estimateMinutes = (int) ceil($item->total_questions / 6); // 约 6 题/分钟
        }
        if ($estimateMinutes < 1) {
            $estimateMinutes = 15;
        }

        $data = [
            'id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'description' => $item->description ?? '',
            'total_questions' => (int) $item->total_questions,
            'price' => (string) $item->price,
            'estimate_minutes' => $estimateMinutes,
        ];

        return $this->success($data);
    }
}
