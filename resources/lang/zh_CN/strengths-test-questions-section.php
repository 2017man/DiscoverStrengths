<?php
return [
    'labels' => [
        'StrengthsTestQuestionsSection' => '题目分组',
        'strengths-test-questions-section' => '题目分组',
    ],
    'fields' => [
        'id' => 'ID',
        'test_type' => '测试类型',
        'section_code' => '分组代码',
        'section_title' => '题目标题/引导语',
        'has_questions' => '是否有题目',
        'sort' => '排序',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'options' => [
        'has_questions' => [0 => '无', 1 => '有'],
    ],
];
