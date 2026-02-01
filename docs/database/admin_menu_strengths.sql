-- 发现你的天赋优势 - 后台菜单数据（admin_menu）
-- 执行前请确认表 admin_menu 存在；若已通过迁移添加过，请勿重复执行
-- 表结构：id, parent_id, order, title, icon, uri, show, extension, created_at, updated_at

SET NAMES utf8mb4;

-- 一级菜单
INSERT INTO `admin_menu` (`parent_id`, `order`, `title`, `icon`, `uri`, `show`, `extension`, `created_at`, `updated_at`)
VALUES (0, 99, '发现你的天赋优势', 'feather icon-award', NULL, 1, '', NOW(), NOW());

SET @parent_id = LAST_INSERT_ID();

-- 二级菜单
INSERT INTO `admin_menu` (`parent_id`, `order`, `title`, `icon`, `uri`, `show`, `extension`, `created_at`, `updated_at`) VALUES
(@parent_id, 1, '测试类型', 'feather icon-book', 'strengths_test_types', 1, '', NOW(), NOW()),
(@parent_id, 2, '计分规则', 'feather icon-sliders', 'strengths_test_scoring_rules', 1, '', NOW(), NOW()),
(@parent_id, 3, '题目分组', 'feather icon-list', 'strengths_test_questions_section', 1, '', NOW(), NOW()),
(@parent_id, 4, '题目', 'feather icon-file-text', 'strengths_test_questions', 1, '', NOW(), NOW()),
(@parent_id, 5, '题目选项', 'feather icon-check-square', 'strengths_test_question_options', 1, '', NOW(), NOW()),
(@parent_id, 6, '四个维度', 'feather icon-layers', 'strengths_test_dimensions', 1, '', NOW(), NOW()),
(@parent_id, 7, '八个面', 'feather icon-grid', 'strengths_test_dimension_sides', 1, '', NOW(), NOW()),
(@parent_id, 8, '测试答案', 'feather icon-book-open', 'strengths_test_answer', 1, '', NOW(), NOW()),
(@parent_id, 9, '用户测试记录', 'feather icon-users', 'strengths_test_results_records', 1, '', NOW(), NOW()),
(@parent_id, 10, '订单', 'feather icon-shopping-cart', 'strengths_orders', 1, '', NOW(), NOW());
