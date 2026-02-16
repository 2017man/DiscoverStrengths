-- 《发现你的天赋优势》数据库建表脚本（无 JSON，适配 Navicat 从 CSV 导入）
-- 规则：表名统一以 strengths_ 为前缀；关联测试类型用 test_type（存 code，如 MBTI），十六种性格一张表，四个维度/八个面各一张表
-- 适用：MySQL 5.7+
-- 字符集：utf8mb4
-- 执行前请先创建数据库：CREATE DATABASE IF NOT EXISTS discover_strengths DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; USE discover_strengths;
--
-- 执行说明：
--   新库：执行至 SET FOREIGN_KEY_CHECKS = 1 即可（建表已含全部字段）
--   已有库升级：仅执行末尾「变更/迁移」部分的 ALTER 语句

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- 1. 测试类型表 ← 01-测试类型.csv
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_types`;
CREATE TABLE `strengths_test_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试代码：MBTI/HOLLAND/ANCHOR/DISC，作为 test_type 关联用',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试名称',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '测试说明',
  `total_questions` int(11) NOT NULL DEFAULT '0' COMMENT '总题数',
  `estimate_minutes` int(11) DEFAULT NULL COMMENT '建议答题时长（分钟），用于 mbti/intro 说明页',
  `price` decimal(10,2) NOT NULL DEFAULT '8.88' COMMENT '单价（元），后台可配置；CSV 无此列时用默认值',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1启用 0禁用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `strengths_test_types_code_unique` (`code`),
  KEY `strengths_test_types_code_index` (`code`),
  KEY `strengths_test_types_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='测试类型';

-- ----------------------------
-- 2. 计分规则表 ← 01-计分规则.csv
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_scoring_rules`;
CREATE TABLE `strengths_test_scoring_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，关联 strengths_test_types.code',
  `rule_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则名称',
  `rule_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则类型：dimension/type/anchor',
  `calculate_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '计算方法',
  `top_count` int(11) DEFAULT NULL COMMENT '取前N个类型（如霍兰德取3）',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '规则说明',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `strengths_test_scoring_rules_test_type_unique` (`test_type`),
  KEY `strengths_test_scoring_rules_test_type_index` (`test_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='计分规则';

-- ----------------------------
-- 3. 题目分组表（题目标题，MBTI 下一/二/三/四；部分分组无题目仅有标题）
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_questions_section`;
CREATE TABLE `strengths_test_questions_section` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，关联 strengths_test_types.code',
  `section_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '题目分组代码，如 一/二/三/四',
  `section_title` text COLLATE utf8mb4_unicode_ci COMMENT '题目标题/引导语，如「哪一个答案最能贴切的描绘你一般的感受或行为?」',
  `has_questions` tinyint(4) NOT NULL DEFAULT '1' COMMENT '该分组下是否有题目：1有 0无（如 MBTI 二、四为选词题，可能无传统题目）',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `strengths_test_questions_section_test_type_section_code_unique` (`test_type`,`section_code`),
  KEY `strengths_test_questions_section_test_type_index` (`test_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='题目分组（题目标题；MBTI 下不是每个分组下都有题目，但一定有选项）';

-- ----------------------------
-- 4. 题目表 ← 02-题目-题目.csv
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_questions`;
CREATE TABLE `strengths_test_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，关联 strengths_test_types.code',
  `section_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '题目分组代码，如 一/二/三/四（MBTI只有一、三有题目，二、四无对应题目，只有对应的选项）',
  `question_number` int(11) NOT NULL COMMENT '题号',
  `question_text` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '题目内容',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `strengths_test_questions_test_type_question_number_index` (`test_type`,`question_number`),
  KEY `strengths_test_questions_test_type_index` (`test_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='题目';

-- ----------------------------
-- 5. 题目选项表 ← 02-题目-题目选项.csv
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_question_options`;
CREATE TABLE `strengths_test_question_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，关联 strengths_test_types.code',
  `section_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '题目分组代码，与 strengths_test_questions.section_code 对应',
  `question_number` int(11) NOT NULL COMMENT '题号，与 strengths_test_questions.question_number 对应，但是最终以这个为准',
  `option_key` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '选项标签，如 A/B',
  `option_text` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '选项内容',
  `dimension_side` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '对应八个面之一：E/I/S/N/T/F/J/P',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `strengths_test_question_options_lookup` (`test_type`,`section_code`,`question_number`),
  KEY `strengths_test_question_options_test_type_index` (`test_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='题目选项（计分用 dimension_side）';

-- ----------------------------
-- 6. MBTI 四个维度表 ← 03-答案-MBTI的四个维度.csv
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_dimensions`;
CREATE TABLE `strengths_test_dimensions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，如 MBTI',
  `dimension_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '维度代码：E-I/S-N/T-F/J-P',
  `dimension_aspect` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '维度方面：动力、信息收集、决策方式、做事方式',
  `dimension_trait_pair` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '维度特质对：内向/外向、感觉/直觉、思考/情感、判断/感知',
  `dimension_scope` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '该维度的主题/范畴（一句话界定），如「与世界的相互作用方式」「获取信息的主要方式」',
  `dimension_summary` text COLLATE utf8mb4_unicode_ci COMMENT '一句话概括或核心提问，如「将注意力集中在何处，从哪里获得动力」',
  `dimension_narrative` text COLLATE utf8mb4_unicode_ci COMMENT '该维度的详细说明正文（含两端特质对比）',
  `dimension_context` text COLLATE utf8mb4_unicode_ci COMMENT '延伸说明或背景语境（可选，部分维度有；如「如果只能用一个维度区分人……」）',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `strengths_test_dimensions_test_type_index` (`test_type`),
  KEY `strengths_test_dimensions_dimension_code_index` (`dimension_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='MBTI 四个维度说明';

-- ----------------------------
-- 7. 四个维度八个面表 ← 03-答案-四个维度八个面.csv
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_dimension_sides`;
CREATE TABLE `strengths_test_dimension_sides` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，如 MBTI',
  `dimension_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '维度代码：E-I/S-N/T-F/J-P，关联 strengths_test_dimensions.dimension_code',
  `side_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '面代码：E/I/S/N/T/F/J/P',
  `side_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '面名称：外向、内向、感觉、直觉、思考、情感、判断、感知',
  `name_en` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '英文名',
  `overview` text COLLATE utf8mb4_unicode_ci COMMENT '概述',
  `features` text COLLATE utf8mb4_unicode_ci COMMENT '特点',
  `keywords` text COLLATE utf8mb4_unicode_ci COMMENT '关键词',
  `style` text COLLATE utf8mb4_unicode_ci COMMENT '行为风格倾向',
  `expression` text COLLATE utf8mb4_unicode_ci COMMENT '外在表现',
  `mantra` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '口头禅',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `strengths_test_dimension_sides_test_type_index` (`test_type`),
  KEY `strengths_test_dimension_sides_dimension_code_index` (`dimension_code`),
  KEY `strengths_test_dimension_sides_side_code_index` (`side_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='四个维度八个面说明';

-- ----------------------------
-- 8. 测试答案表（存储各类型测试结果的解释内容）← 03-答案-十六种性格类型.csv
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_answer`;
CREATE TABLE `strengths_test_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，如 MBTI',
  `result_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '结果代码，如 ISTJ、ENFP',
  `result_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色/结果名称，如 检查者、照顾者',
  `summary` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '总括',
  `traits_summary` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '性格特点总括',
  `traits` text COLLATE utf8mb4_unicode_ci COMMENT '性格特点正文',
  `strengths` text COLLATE utf8mb4_unicode_ci COMMENT '优势',
  `weaknesses` text COLLATE utf8mb4_unicode_ci COMMENT '劣势',
  `careers` text COLLATE utf8mb4_unicode_ci COMMENT '适合的职业',
  `suggestion` text COLLATE utf8mb4_unicode_ci COMMENT '沟通与成长建议',
  `typical_figures` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '典型人物',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1启用 0禁用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `strengths_test_answer_test_type_result_code_unique` (`test_type`,`result_code`),
  KEY `strengths_test_answer_test_type_index` (`test_type`),
  KEY `strengths_test_answer_result_code_index` (`result_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='测试答案（MBTI十六种性格类型等测试结果的解释内容）';

-- ----------------------------
-- 9. 用户测试记录表（由应用写入，非 CSV 导入）
-- ----------------------------
DROP TABLE IF EXISTS `strengths_test_results_records`;
CREATE TABLE `strengths_test_results_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码，关联 strengths_test_types.code',
  `result_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '结果代码，如 INTJ',
  `openid` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信 openid（防重复付费）',
  `session_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '匿名会话标识',
  `answers_snapshot` text COLLATE utf8mb4_unicode_ci COMMENT '答案快照（可选，存文本）',
  `e_score` int(11) NOT NULL DEFAULT '0' COMMENT '外向(E)得分',
  `i_score` int(11) NOT NULL DEFAULT '0' COMMENT '内向(I)得分',
  `s_score` int(11) NOT NULL DEFAULT '0' COMMENT '实感(S)得分',
  `n_score` int(11) NOT NULL DEFAULT '0' COMMENT '直觉(N)得分',
  `t_score` int(11) NOT NULL DEFAULT '0' COMMENT '思考(T)得分',
  `f_score` int(11) NOT NULL DEFAULT '0' COMMENT '情感(F)得分',
  `j_score` int(11) NOT NULL DEFAULT '0' COMMENT '判断(J)得分',
  `p_score` int(11) NOT NULL DEFAULT '0' COMMENT '知觉(P)得分',
  `is_paid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已付费：0否 1是',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT '付费时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `strengths_test_results_records_test_type_index` (`test_type`),
  KEY `strengths_test_results_records_openid_index` (`openid`),
  KEY `strengths_test_results_records_openid_test_type_index` (`openid`,`test_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户测试记录';

-- ----------------------------
-- 10. 订单表（由应用写入，非 CSV 导入）
-- ----------------------------
DROP TABLE IF EXISTS `strengths_orders`;
CREATE TABLE `strengths_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `out_trade_no` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商户订单号',
  `epay_order_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '易支付云端订单号，用于 getOrder/checkOrder',
  `test_result_id` bigint(20) unsigned NOT NULL COMMENT '关联 strengths_test_results_records.id',
  `test_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '测试类型代码',
  `openid` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信 openid',
  `amount` decimal(10,2) NOT NULL COMMENT '金额（元）',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pending/paid/failed/closed',
  `pay_channel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付渠道（如 epay）',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT '支付成功时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `strengths_orders_out_trade_no_unique` (`out_trade_no`),
  KEY `strengths_orders_test_result_id_index` (`test_result_id`),
  KEY `strengths_orders_openid_index` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单';

-- ----------------------------
-- 11. 站点配置表（供 GET /config/site 使用）
-- ----------------------------
DROP TABLE IF EXISTS `strengths_site_config`;
CREATE TABLE `strengths_site_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stats_count` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '测试总人次',
  `stats_date` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '统计日期文案，如 2014年5月19日 ~ 至今',
  `qrcode_wechat` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '公众号二维码图片 URL',
  `qrcode_community` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '社群二维码图片 URL',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='站点配置（首页 stats、二维码）';

-- 站点配置初始数据（供 GET /config/site 使用）
INSERT INTO `strengths_site_config` (`stats_count`, `stats_date`, `qrcode_wechat`, `qrcode_community`)
VALUES ('0', '2014年5月19日 ~ 至今', NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- 变更/迁移：已有数据库升级用（新库请勿执行，建表已包含下述字段）
-- =============================================================================

-- 变更：strengths_test_types 添加 estimate_minutes
-- ALTER TABLE `strengths_test_types` ADD COLUMN `estimate_minutes` int(11) DEFAULT NULL COMMENT '建议答题时长（分钟）' AFTER `total_questions`;

-- 变更：strengths_test_answer 添加 suggestion
-- ALTER TABLE `strengths_test_answer` ADD COLUMN `suggestion` text COLLATE utf8mb4_unicode_ci COMMENT '沟通与成长建议' AFTER `careers`;

-- 变更：strengths_test_results_records 添加 8 个维度得分字段
-- ALTER TABLE `strengths_test_results_records`
--   ADD COLUMN `e_score` int(11) NOT NULL DEFAULT 0 COMMENT '外向(E)得分' AFTER `answers_snapshot`,
--   ADD COLUMN `i_score` int(11) NOT NULL DEFAULT 0 COMMENT '内向(I)得分' AFTER `e_score`,
--   ADD COLUMN `s_score` int(11) NOT NULL DEFAULT 0 COMMENT '实感(S)得分' AFTER `i_score`,
--   ADD COLUMN `n_score` int(11) NOT NULL DEFAULT 0 COMMENT '直觉(N)得分' AFTER `s_score`,
--   ADD COLUMN `t_score` int(11) NOT NULL DEFAULT 0 COMMENT '思考(T)得分' AFTER `n_score`,
--   ADD COLUMN `f_score` int(11) NOT NULL DEFAULT 0 COMMENT '情感(F)得分' AFTER `t_score`,
--   ADD COLUMN `j_score` int(11) NOT NULL DEFAULT 0 COMMENT '判断(J)得分' AFTER `f_score`,
--   ADD COLUMN `p_score` int(11) NOT NULL DEFAULT 0 COMMENT '知觉(P)得分' AFTER `j_score`;
