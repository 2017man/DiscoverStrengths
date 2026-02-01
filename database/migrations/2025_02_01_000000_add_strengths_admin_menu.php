<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddStrengthsAdminMenu extends Migration
{
    /**
     * Run the migrations.
     * 为「发现你的天赋优势」后台添加 admin_menu 菜单数据
     */
    public function up()
    {
        $exists = DB::table('admin_menu')->where('title', '发现你的天赋优势')->where('parent_id', 0)->exists();
        if ($exists) {
            return;
        }

        $now = now()->format('Y-m-d H:i:s');

        // 一级菜单：发现你的天赋优势
        $parentId = DB::table('admin_menu')->insertGetId([
            'parent_id' => 0,
            'order' => 99,
            'title' => '发现你的天赋优势',
            'icon' => 'feather icon-award',
            'uri' => null,
            'show' => 1,
            'extension' => '',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $children = [
            ['order' => 1, 'title' => '测试类型', 'uri' => 'strengths_test_types', 'icon' => 'feather icon-book'],
            ['order' => 2, 'title' => '计分规则', 'uri' => 'strengths_test_scoring_rules', 'icon' => 'feather icon-sliders'],
            ['order' => 3, 'title' => '题目分组', 'uri' => 'strengths_test_questions_section', 'icon' => 'feather icon-list'],
            ['order' => 4, 'title' => '题目', 'uri' => 'strengths_test_questions', 'icon' => 'feather icon-file-text'],
            ['order' => 5, 'title' => '题目选项', 'uri' => 'strengths_test_question_options', 'icon' => 'feather icon-check-square'],
            ['order' => 6, 'title' => '四个维度', 'uri' => 'strengths_test_dimensions', 'icon' => 'feather icon-layers'],
            ['order' => 7, 'title' => '八个面', 'uri' => 'strengths_test_dimension_sides', 'icon' => 'feather icon-grid'],
            ['order' => 8, 'title' => '测试答案', 'uri' => 'strengths_test_answer', 'icon' => 'feather icon-book-open'],
            ['order' => 9, 'title' => '用户测试记录', 'uri' => 'strengths_test_results_records', 'icon' => 'feather icon-users'],
            ['order' => 10, 'title' => '订单', 'uri' => 'strengths_orders', 'icon' => 'feather icon-shopping-cart'],
        ];

        foreach ($children as $item) {
            DB::table('admin_menu')->insert([
                'parent_id' => $parentId,
                'order' => $item['order'],
                'title' => $item['title'],
                'icon' => $item['icon'],
                'uri' => $item['uri'],
                'show' => 1,
                'extension' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $parent = DB::table('admin_menu')->where('title', '发现你的天赋优势')->where('parent_id', 0)->first();
        if ($parent) {
            DB::table('admin_menu')->where('parent_id', $parent->id)->delete();
            DB::table('admin_menu')->where('id', $parent->id)->delete();
        }
    }
}
