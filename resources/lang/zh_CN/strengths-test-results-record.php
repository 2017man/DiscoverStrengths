<?php
return [
    'labels' => [
        'StrengthsTestResultsRecord' => '用户测试记录',
        'strengths-test-results-record' => '用户测试记录',
    ],
    'fields' => [
        'id' => 'ID',
        'test_type' => '测试类型',
        'result_code' => '结果代码',
        'openid' => 'OpenID',
        'session_id' => '会话ID',
        'answers_snapshot' => '答案快照',
        'is_paid' => '是否已付费',
        'paid_at' => '付费时间',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'options' => [
        'is_paid' => [0 => '未付费', 1 => '已付费'],
    ],
];
