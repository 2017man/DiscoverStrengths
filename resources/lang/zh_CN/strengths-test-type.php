<?php
return [
    'labels' => [
        'StrengthsTestType' => '测试类型',
        'strengths-test-type' => '测试类型',
    ],
    'fields' => [
        'id' => 'ID',
        'code' => '测试代码',
        'name' => '测试名称',
        'description' => '说明',
        'total_questions' => '总题数',
        'price' => '单价(元)',
        'sort' => '排序',
        'status' => '状态',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'options' => [
        'status' => [0 => '禁用', 1 => '启用'],
    ],
];
