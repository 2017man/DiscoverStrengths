<?php

namespace App\Http\Controllers\Api;

use App\Http\Service\EpayService;
use Illuminate\Http\Request;

/**
 * 支付回调（易支付异步通知等）
 * 不校验登录；notify 由易支付服务器 GET 调用（文档：向异步通知地址发送 GET 请求）
 */
class PaymentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->isNeedLogin = false;
    }

    /**
     * 易支付异步通知
     * GET /api/v1/payment/epay/notify
     * 文档：易支付收到用户收款后，向异步通知地址发送 GET 请求；验签 md5(orderId+param+type+price+reallyPrice+通讯密钥)；需返回字符串 "success"
     */
    public function epayNotify(Request $request)
    {
        $params = $request->all();
        $epay = new EpayService();

        if (!$epay->handleNotify($params)) {
            return response('fail', 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
        }

        return response('success', 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * 未配置易支付时的占位页（可选）
     * GET /api/v1/payment/epay/placeholder?out_trade_no=xxx
     */
    public function epayPlaceholder(Request $request)
    {
        $outTradeNo = $request->get('out_trade_no', '');
        return response()->json([
            'code' => 200,
            'msg' => '请在后端配置易支付（.env 中 EPAY_MCH_ID、EPAY_KEY、EPAY_API_CREATE_ORDER、EPAY_NOTIFY_URL 等，见 docs/易支付配置说明.md）',
            'data' => ['out_trade_no' => $outTradeNo],
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
