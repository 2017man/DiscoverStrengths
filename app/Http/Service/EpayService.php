<?php

namespace App\Http\Service;

use App\Models\StrengthsOrder;
use App\Models\StrengthsTestResultsRecord;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * 易支付对接（与 docs/易支付开发文档.md 一致）
 * 创建订单：请求易支付 /api/createOrder，签名 md5(payId+param+type+price+通讯密钥)
 * 回调：GET 请求，验签 md5(orderId+param+type+price+reallyPrice+通讯密钥)，根据 payId 查单
 */
class EpayService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('epay');
    }

    /**
     * 是否已配置并可用
     */
    public function isEnabled(): bool
    {
        return !empty($this->config['enabled'])
            && $this->config['mch_id'] !== ''
            && $this->config['key'] !== ''
            && $this->config['api_create_order'] !== '';
    }

    /**
     * 创建订单签名：md5(payId+param+type+price+通讯密钥)
     * 文档：sign = md5(payId+param+type+price+通讯密钥)
     */
    public function buildCreateOrderSign(string $payId, string $param, int $type, $price): string
    {
        $str = $payId . $param . (string) $type . (string) $price . $this->config['key'];
        return strtolower(md5($str));
    }

    /**
     * 回调验签：md5(orderId+param+type+price+reallyPrice+通讯密钥)
     * 文档：sign = md5(orderId + param + type + price + reallyPrice + 通讯密钥)
     */
    public function buildNotifySign(string $orderId, string $param, $type, $price, $reallyPrice): string
    {
        $str = $orderId . $param . (string) $type . (string) $price . (string) $reallyPrice . $this->config['key'];
        return strtolower(md5($str));
    }

    public function verifyNotifySign(array $params): bool
    {
        $sign = $params['sign'] ?? '';
        if ($sign === '') {
            return false;
        }
        $orderId = (string) ($params['orderId'] ?? '');
        $param = (string) ($params['param'] ?? '');
        $type = $params['type'] ?? '';
        $price = (string) ($params['price'] ?? '');
        $reallyPrice = (string) ($params['reallyPrice'] ?? '');
        $expected = $this->buildNotifySign($orderId, $param, $type, $price, $reallyPrice);
        return strtolower($sign) === $expected;
    }

    /**
     * 调用易支付创建订单接口，返回支付链接（payUrl）或跳转地址
     * 文档：GET/POST https://epay.jylt.cc/api/createOrder
     * 参数：mchId, payId, type, price, sign, goodsName, param, isHtml, notifyUrl, returnUrl
     */
    public function createOrder(string $payId, $price, string $goodsName, string $param = '', ?string $returnUrl = null): array
    {
        $type = (int) $this->config['type'];
        $priceStr = (string) $price;
        $sign = $this->buildCreateOrderSign($payId, $param, $type, $priceStr);

        $notifyUrl = $this->config['notify_url'] ?: url('/api/v1/payment/epay/notify');
        $returnUrl = $returnUrl ?: $this->config['return_url'];
        $isHtml = (int) ($this->config['is_html'] ?? 0);

        $requestParams = [
            'mchId' => (string) $this->config['mch_id'],
            'payId' => $payId,
            'type' => $type,
            'price' => $priceStr,
            'sign' => $sign,
            'goodsName' => mb_substr($goodsName, 0, 50), // 文档：长度最大50个字符
            'isHtml' => $isHtml,
            'notifyUrl' => $notifyUrl,
        ];
        if ($returnUrl !== '') {
            $requestParams['returnUrl'] = $returnUrl;
        }
        if ($param !== '') {
            $requestParams['param'] = $param;
        }

        $apiUrl = $this->config['api_create_order'];
        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->post($apiUrl, ['form_params' => $requestParams]);
            $body = json_decode((string) $response->getBody(), true) ?? [];
        } catch (\Throwable $e) {
            Log::warning('Epay createOrder request failed', ['message' => $e->getMessage()]);
            return ['success' => false, 'pay_url' => '', 'message' => '易支付请求失败'];
        }

        if ($response->getStatusCode() !== 200) {
            Log::warning('Epay createOrder non-200', ['status' => $response->getStatusCode(), 'body' => $body]);
            return ['success' => false, 'pay_url' => '', 'message' => '易支付请求失败'];
        }
        $code = $body['code'] ?? -1;
        if ((int) $code !== 1) {
            Log::warning('Epay createOrder api error', ['body' => $body]);
            return ['success' => false, 'pay_url' => '', 'message' => $body['msg'] ?? '创建订单失败'];
        }

        $data = $body['data'] ?? null;
        if ($data === null) {
            return ['success' => false, 'pay_url' => '', 'message' => '易支付返回数据为空'];
        }

        // isHtml=1 时 data 可能为跳转 url 字符串；否则 data 为对象含 payUrl
        $payUrl = is_string($data) ? $data : ($data['payUrl'] ?? '');
        if ($payUrl !== '' && str_contains($payUrl, '\\u003d')) {
            $payUrl = str_replace('\\u003d', '=', $payUrl); // 文档：将 \u003d 替换为 =
        }
        $orderId = is_array($data) ? ($data['orderId'] ?? '') : '';

        return ['success' => true, 'pay_url' => $payUrl, 'order_id' => $orderId];
    }

    /**
     * 获取易支付「查询订单」接口 URL（从 api_create_order 派生）
     */
    protected function getApiGetOrderUrl(): string
    {
        $url = $this->config['api_get_order'] ?? '';
        if ($url !== '') {
            return $url;
        }
        $create = $this->config['api_create_order'] ?? '';
        return preg_replace('#/api/createOrder$#i', '/api/getOrder', $create);
    }

    /**
     * 获取易支付「查询订单状态」接口 URL（从 api_create_order 派生）
     */
    protected function getApiCheckOrderUrl(): string
    {
        $url = $this->config['api_check_order'] ?? '';
        if ($url !== '') {
            return $url;
        }
        $create = $this->config['api_create_order'] ?? '';
        return preg_replace('#/api/createOrder$#i', '/api/checkOrder', $create);
    }

    /**
     * 查询订单信息（易支付 getOrder）
     * 文档：GET/POST 参数 orderId、mchId，返回订单详情
     */
    public function getOrder(string $orderId): array
    {
        $apiUrl = $this->getApiGetOrderUrl();
        $params = [
            'orderId' => $orderId,
            'mchId' => (string) $this->config['mch_id'],
        ];
        try {
            $client = new Client(['timeout' => 8]);
            $response = $client->get($apiUrl, ['query' => $params]);
            $body = json_decode((string) $response->getBody(), true) ?? [];
        } catch (\Throwable $e) {
            Log::warning('Epay getOrder request failed', ['message' => $e->getMessage()]);
            return ['success' => false, 'data' => null, 'message' => '查询失败'];
        }
        $code = $body['code'] ?? -1;
        if ((int) $code !== 1) {
            return ['success' => false, 'data' => null, 'message' => $body['msg'] ?? '查询失败'];
        }
        return ['success' => true, 'data' => $body['data'] ?? null];
    }

    /**
     * 查询订单状态（易支付 checkOrder）
     * 文档：GET/POST 参数 orderId、mchId；code 1=已支付 -1=失败 -2=未支付 -3=已过期
     */
    public function checkOrder(string $orderId): array
    {
        $apiUrl = $this->getApiCheckOrderUrl();
        $params = [
            'orderId' => $orderId,
            'mchId' => (string) $this->config['mch_id'],
        ];
        try {
            $client = new Client(['timeout' => 8]);
            $response = $client->get($apiUrl, ['query' => $params]);
            $body = json_decode((string) $response->getBody(), true) ?? [];
        } catch (\Throwable $e) {
            Log::warning('Epay checkOrder request failed', ['message' => $e->getMessage()]);
            return ['success' => false, 'code' => null, 'paid' => false, 'message' => '查询失败'];
        }
        $code = (int) ($body['code'] ?? -1);
        $paid = $code === 1;
        return [
            'success' => true,
            'code' => $code,
            'paid' => $paid,
            'data' => $body['data'] ?? null,
            'message' => $body['msg'] ?? '',
        ];
    }

    /**
     * 处理异步通知（GET 请求）
     * 文档：易支付向异步通知地址发送 GET 请求，参数 mchId, payId, orderId, param, type, price, reallyPrice, sign
     * 需返回字符串 "success"
     */
    public function handleNotify(array $params): bool
    {
        if (!$this->verifyNotifySign($params)) {
            Log::warning('Epay notify sign failed', ['params' => $params]);
            return false;
        }

        $payId = (string) ($params['payId'] ?? '');
        if ($payId === '') {
            Log::warning('Epay notify missing payId', ['params' => $params]);
            return false;
        }

        $order = StrengthsOrder::query()->where('out_trade_no', $payId)->first();
        if (!$order) {
            Log::warning('Epay notify order not found', ['payId' => $payId]);
            return false;
        }
        if ($order->status === 'paid') {
            return true; // 已处理过，直接返回 success 避免重复通知
        }

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $record = StrengthsTestResultsRecord::query()->find($order->test_result_id);
        if ($record) {
            $record->update([
                'is_paid' => 1,
                'paid_at' => now(),
            ]);
        }

        return true;
    }
}
