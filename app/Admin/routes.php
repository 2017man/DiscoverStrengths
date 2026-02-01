<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;
use App\Admin\Controllers\GatherInformationController;

Admin::routes();

/**
 * 路由覆盖
 */
$attributes = [
    'prefix'     => config('admin.route.prefix'),
    'middleware' => config('admin.route.middleware'),
];
app('router')->group($attributes, function ($router) {
    //TODO 用户重写
    $router->resource('auth/users', 'App\Admin\Controllers\UserNewController');
});

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('report', 'ReportController@index'); // 客情工作服务力月报
    /**
     * 配置
     */
    $router->resource('HelperDictionary', 'HelperDictionaryController');
    $router->resource('HelperDictionaryInfo', 'HelperDictionaryInfoController');
    $router->resource('project', 'ProjectController');
    $router->resource('company_config', 'CompanyConfigController');// 集团单位基础
    /**
     * 人员数据
     */
    $router->resource('member', 'MemberController');// 员工信息
    $router->resource('member_relation', 'MemberRelationController');// 亲属
    $router->resource('gather_information', 'GatherInformationController');// 收集信息

    /**
     * 集团信息
     */
    $router->resource('company', 'CompanyController');// 集团单位基本信息表
    $router->resource('company_member', 'CompanyMemberController');// 集团信息--成员信息
    $router->resource('company_special', 'CompanySpecialController');// 集团信息--专线信息
    $router->resource('company_other', 'CompanyOtherController');// 集团信息--其他信息化产品信息
    /**
     * 客情输出
     */
    $router->resource('company_out_report', 'CompanyOutReportController');// 工作报告
    $router->resource('company_out_visit', 'CompanyOutVisitController');// 拜访交流
    $router->resource('company_out_marketing', 'CompanyOutMarketingController');// 进集团营销
    $router->resource('company_out_research', 'CompanyOutResearchController');// 调研参观
    $router->resource('company_out_evaluation', 'CompanyOutEvaluationController');// 满意度测评

    /**
     * 考核
     */
    $router->resource('assessment_config', 'AssessmentConfigController');// 考核设置

    /**
     * 消息通知
     */
    $router->resource('gather_information_state', 'GatherInformationStateController');// 状态记录
    $router->resource('gather_information_notification', 'GatherInformationNotificationController');// 消息通知记录
    $router->resource('gather_information_sms', 'GatherInformationSmsController');// 短信消息通知
    $router->resource('gather_information_count', 'GatherInformationCountController');// 短信消息通知

    /**
     * 支撑考核
     */
    $router->resource('evaluate_config', 'EvaluateConfigController');// 支撑考核模板配置
    $router->resource('evaluate_record', 'EvaluateRecordController');// 支撑评估记录
    $router->resource('evaluate_detail', 'EvaluateDetailController');// 支撑评估明细
    $router->resource('ceping_count', 'CepingCountController');// 支撑评估明细
    // 记着：单个方法的路由一定要放到 resource 资源路由前面
    $router->get('ceping_count_brief/export', 'CepingCountBriefController@export')->name('ceping.export');
    $router->get('ceping_count_brief/export_gb', 'CepingCountBriefController@exportGb')->name('ceping.exportGb');
    $router->resource('ceping_count_brief', 'CepingCountBriefController');// 支撑评估明细

    /**
     * 发现你的天赋优势 - 后台管理
     */
    $router->resource('strengths_test_types', 'StrengthsTestTypeController');           // 测试类型
    $router->resource('strengths_test_scoring_rules', 'StrengthsTestScoringRuleController'); // 计分规则
    $router->resource('strengths_test_questions_section', 'StrengthsTestQuestionsSectionController'); // 题目分组
    $router->resource('strengths_test_questions', 'StrengthsTestQuestionController'); // 题目
    $router->resource('strengths_test_question_options', 'StrengthsTestQuestionOptionController'); // 题目选项
    $router->resource('strengths_test_dimensions', 'StrengthsTestDimensionController'); // 四个维度
    $router->resource('strengths_test_dimension_sides', 'StrengthsTestDimensionSideController');   // 八个面
    $router->resource('strengths_test_answer', 'StrengthsTestAnswerController');       // 测试答案(十六种性格)
    $router->resource('strengths_test_results_records', 'StrengthsTestResultsRecordController'); // 用户测试记录
    $router->resource('strengths_orders', 'StrengthsOrderController');                 // 订单

});
