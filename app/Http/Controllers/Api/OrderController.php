<?php

namespace App\Http\Controllers\Api;

use App\Http\Service\EpayService;
use App\Models\StrengthsTestResultsRecord;
use App\Models\StrengthsTestType;
use App\Models\StrengthsOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->isNeedLogin = false;
        parent::__construct();
    }

    /**
     * 创建订单（获取支付链接）
     * POST /api/v1/order/create  body: { "result_id": 1001 }
     */
    public function create(Request $request)
    {
        $resultId = (int) $request->input('result_id');
        if (!$resultId) {
            return $this->error('10007', '缺少 result_id');
        }

        $record = StrengthsTestResultsRecord::query()->find($resultId);
        if (!$record) {
            return $this->error('10005', '测试记录不存在');
        }
        if ($record->test_type !== 'MBTI') {
            return $this->error('10008', '暂仅支持 MBTI 报告付费');
        }
        if ((int) $record->is_paid === 1) {
            return $this->error('10009', '该报告已付费，无需重复下单');
        }

        $testType = StrengthsTestType::query()->where('code', $record->test_type)->first();
        if (!$testType) {
            return $this->error('10010', '测试类型配置不存在');
        }

        $amount = $testType->price;
        $outTradeNo = 'DS' . date('YmdHis') . rand(1000, 9999);

        $order = StrengthsOrder::create([
            'out_trade_no' => $outTradeNo,
            'test_result_id' => $record->id,
            'test_type' => $record->test_type,
            'openid' => $record->openid,
            'amount' => $amount,
            'status' => 'pending',
            'pay_channel' => 'epay',
        ]);

        $payUrl = '';
        $skipPaymentForTest = (bool) config('epay.skip_payment_for_test', false);

        if ($skipPaymentForTest) {
            // 测试阶段免支付：不调用易支付，直接标记订单与测试记录为已支付，返回报告页地址供前端跳转
            $order->update(['status' => 'paid', 'paid_at' => now()]);
            $record->update(['is_paid' => 1, 'paid_at' => now()]);
            $returnUrlBase = (string) config('epay.return_url_base', '');
            $payUrl = $returnUrlBase !== ''
                ? rtrim($returnUrlBase, '/') . '?result_id=' . $record->id
                : url('/api/v1/payment/epay/placeholder?out_trade_no=' . $outTradeNo . '&result_id=' . $record->id);
        } else {
            $epay = new EpayService();
            if ($epay->isEnabled()) {
                $goodsName = $record->test_type . ' 测试报告 - ' . $record->result_code;
                $returnUrl = (string) config('epay.return_url');
                if ($returnUrl === '' && (string) config('epay.return_url_base') !== '') {
                    $returnUrl = rtrim(config('epay.return_url_base'), '/') . '?result_id=' . $record->id;
                }
                $param = (string) $record->id; // 回调会原样带回，便于对账
                $result = $epay->createOrder($outTradeNo, $amount, $goodsName, $param, $returnUrl ?: null);
                if ($result['success'] && $result['pay_url'] !== '') {
                    $payUrl = $result['pay_url'];
                    if (!empty($result['order_id'])) {
                        $order->update(['epay_order_id' => $result['order_id']]);
                    }
                }
            }
            if ($payUrl === '') {
                $payUrl = url('/api/v1/payment/epay/placeholder?out_trade_no=' . $outTradeNo);
            }
        }

        $data = [
            'order_id' => $outTradeNo,
            'amount' => (string) $amount,
            'pay_url' => $payUrl,
            'out_trade_no' => $outTradeNo,
        ];
        if (!empty($order->epay_order_id)) {
            $data['epay_order_id'] = $order->epay_order_id; // 用于前端轮询 checkOrder 或后端查询
        }
        if ($skipPaymentForTest) {
            $data['skip_payment'] = true; // 前端可据此直接跳转报告页或提示「测试模式已免支付」
        }

        return $this->success($data);
    }

    /**
     * 查询支付状态（轮询易支付 checkOrder）
     * GET /api/v1/order/check-payment?out_trade_no=xxx
     * 返回：paid(true/false)、state(1=已支付 0=等待 -2=未支付 -3=已过期)、message
     */
    public function checkPayment(Request $request)
    {
        $outTradeNo = $request->get('out_trade_no', '');
        if ($outTradeNo === '') {
            return $this->error('10011', '缺少 out_trade_no');
        }

        $order = StrengthsOrder::query()->where('out_trade_no', $outTradeNo)->first();
        if (!$order) {
            return $this->error('10012', '订单不存在');
        }
        if ($order->status === 'paid') {
            return $this->success([
                'out_trade_no' => $outTradeNo,
                'paid' => true,
                'state' => 1,
                'message' => '已支付',
            ]);
        }

        $epayOrderId = $order->epay_order_id;
        if ($epayOrderId === '' || $epayOrderId === null) {
            return $this->success([
                'out_trade_no' => $outTradeNo,
                'paid' => false,
                'state' => 0,
                'message' => '订单未关联易支付，请稍后或联系客服',
            ]);
        }

        $epay = new EpayService();
        if (!$epay->isEnabled()) {
            return $this->success([
                'out_trade_no' => $outTradeNo,
                'paid' => false,
                'state' => 0,
                'message' => '易支付未配置，仅以本地状态为准',
            ]);
        }

        $result = $epay->checkOrder($epayOrderId);
        if (!$result['success']) {
            return $this->success([
                'out_trade_no' => $outTradeNo,
                'paid' => false,
                'state' => 0,
                'message' => $result['message'] ?? '查询失败',
            ]);
        }

        $state = $result['code'];
        $paid = $result['paid'];
        if ($paid && $order->status !== 'paid') {
            $order->update(['status' => 'paid', 'paid_at' => now()]);
            $record = StrengthsTestResultsRecord::query()->find($order->test_result_id);
            if ($record) {
                $record->update(['is_paid' => 1, 'paid_at' => now()]);
            }
        }

        return $this->success([
            'out_trade_no' => $outTradeNo,
            'paid' => $paid,
            'state' => $state,
            'message' => $result['message'] ?: ($paid ? '已支付' : '未支付'),
        ]);
    }

    /**
     * 订单状态 / 是否已付费
     * GET /api/v1/order/status?result_id=xxx
     */
    public function status(Request $request)
    {
        $resultId = (int) $request->get('result_id');
        if (!$resultId) {
            return $this->error('10004', '缺少 result_id');
        }

        $record = StrengthsTestResultsRecord::query()->find($resultId);
        if (!$record) {
            return $this->error('10005', '测试记录不存在');
        }

        $order = StrengthsOrder::query()
            ->where('test_result_id', $resultId)
            ->where('status', 'paid')
            ->first();

        $data = [
            'result_id' => $record->id,
            'is_paid' => (int) $record->is_paid === 1,
            'order_id' => $order ? $order->out_trade_no : '',
        ];

        return $this->success($data);
    }
}
