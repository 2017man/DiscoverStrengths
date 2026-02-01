<?php
return [
    'labels' => [
        'StrengthsOrder' => '订单',
        'strengths-order' => '订单',
    ],
    'fields' => [
        'id' => 'ID',
        'out_trade_no' => '商户订单号',
        'test_result_id' => '测试记录ID',
        'test_type' => '测试类型',
        'openid' => 'OpenID',
        'amount' => '金额',
        'status' => '状态',
        'pay_channel' => '支付渠道',
        'paid_at' => '支付时间',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'options' => [
        'status' => [
            'pending' => '待支付',
            'paid' => '已支付',
            'failed' => '失败',
            'closed' => '已关闭',
        ],
    ],
];
