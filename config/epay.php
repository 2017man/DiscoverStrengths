<?php

/**
 * 易支付配置（与 docs/易支付开发文档.md 一致，个人配置请在 .env 填写）
 * 创建订单：请求 /api/createOrder，参数 mchId、payId、type、price、goodsName、sign、notifyUrl、returnUrl 等
 * 签名(创建)：md5(payId+param+type+price+通讯密钥)
 * 回调：GET 请求，验签 md5(orderId+param+type+price+reallyPrice+通讯密钥)
 */
return [
    'enabled' => env('EPAY_ENABLED', false),

    // 测试阶段免支付：为 true 时创建订单后不调用易支付，直接视为已支付并返回报告页地址（仅用于测试环境）
    'skip_payment_for_test' => env('PAYMENT_SKIP_FOR_TEST', false),

    // 商户 ID（文档 mchId）
    'mch_id' => env('EPAY_MCH_ID', ''),

    // 通讯密钥（用于签名与验签）
    'key' => env('EPAY_KEY', ''),

    // 创建订单接口地址（文档示例：https://epay.jylt.cc/api/createOrder）
    'api_create_order' => rtrim(env('EPAY_API_CREATE_ORDER', ''), '/'),

    // 查询订单信息接口（可选，不填则从 api_create_order 派生：同域名 /api/getOrder）
    'api_get_order' => rtrim(env('EPAY_API_GET_ORDER', ''), '/'),

    // 查询订单状态接口（可选，不填则从 api_create_order 派生：同域名 /api/checkOrder）
    'api_check_order' => rtrim(env('EPAY_API_CHECK_ORDER', ''), '/'),

    // 异步通知地址（支付成功后易支付 GET 到此地址，须公网可访问）
    'notify_url' => env('EPAY_NOTIFY_URL', ''),

    // 同步跳转地址（isHtml=1 时支付完成后浏览器跳转）
    'return_url' => env('EPAY_RETURN_URL', ''),

    // 前端报告页基础 URL（未填 EPAY_RETURN_URL 时拼接 ?result_id=xxx）
    'return_url_base' => env('EPAY_RETURN_URL_BASE', ''),

    // 支付方式（文档 type）：1=微信 2=支付宝
    'type' => (int) env('EPAY_TYPE', 2),

    // 创建订单是否返回跳转页：0=返回订单 json（含 payUrl 二维码地址） 1=返回跳转页面 url
    'is_html' => (int) env('EPAY_IS_HTML', 0),
];
