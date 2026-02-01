<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\HelperDictionaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->name('api.v1.')->group(function () {
    /**
     * 公共方法
     */
    Route::prefix('com')->group(function () {
        Route::get('options/{code}', [HelperDictionaryController::class, 'options'])->name('dictionary.options');                         // 基础信息
        Route::get('options_api/{code}', [HelperDictionaryController::class, 'optionsApi'])->name('dictionary.options.api');              // 基础信息
        Route::get('options_text_api/{code}', [HelperDictionaryController::class, 'optionsTextApi'])->name('dictionary.options.text.api'); // 基础信息
    });
    /**
     * 配置
     */
    Route::prefix('config')->group(function () {
        Route::post('company', [\App\Http\Controllers\Api\CompanyConfigController::class, 'companyByArea'])->name('config.company'); // 基础信息
    });
    // 用户登录
    Route::post('login', [\App\Http\Controllers\Api\UserController::class, 'login'])->name('login');// 用户登录
    /**
     * 用户
     */

    //Route::middleware('auth.jwt')->group(function () {

    Route::post('refresh_token', [\App\Http\Controllers\Api\UserController::class, 'refreshToken'])->name('refresh_token');   // token刷新
    Route::post('logout', [\App\Http\Controllers\Api\UserController::class, 'logout'])->name('logout');                       // 退出
    Route::post('user_info', [\App\Http\Controllers\Api\UserController::class, 'userInfo'])->name('user_info');               // 用户信息
    Route::post('reset_password', [\App\Http\Controllers\Api\UserController::class, 'resetPassword'])->name('reset_password');// 修改密码
    /**
     * 监督员
     */
    Route::prefix('member_relation')->group(function () {
        Route::post('add', [\App\Http\Controllers\Api\MemberRelationController::class, 'add'])->name('member_relation.add');                     // 添加监督员
        Route::post('lists', [\App\Http\Controllers\Api\MemberRelationController::class, 'lists'])->name('member_relation.lists');               // 添加监督员
        Route::post('my_members', [\App\Http\Controllers\Api\MemberRelationController::class, 'my_members'])->name('member_relation.my_members'); // 添加监督员
        Route::post('detail', [\App\Http\Controllers\Api\MemberRelationController::class, 'detail'])->name('member_relation.detail');            // 监督员详情

    });

    /**
     * 信息收集
     */
    Route::prefix('gather_information')->group(function () {
        Route::post('add', [\App\Http\Controllers\Api\GatherInformationController::class, 'add'])->name('gather_information.add');                         // 添加监督员
        Route::post('lists', [\App\Http\Controllers\Api\GatherInformationController::class, 'lists'])->name('gather_information.lists');                   // 添加监督员
        Route::post('todo_lists', [\App\Http\Controllers\Api\GatherInformationController::class, 'todoLists'])->name('gather_information.todoLists');      // 添加监督员
        Route::post('detail', [\App\Http\Controllers\Api\GatherInformationController::class, 'detail'])->name('gather_information.detail');                // 添加监督员
        Route::post('change_state', [\App\Http\Controllers\Api\GatherInformationController::class, 'changeState'])->name('gather_information.changeState'); // 添加监督员
        Route::post('info_count', [\App\Http\Controllers\Api\GatherInformationController::class, 'infoCount'])->name('gather_information.infoCount');      // 添加监督员

    });
    /**
     * 排行榜
     */
    Route::prefix('rank')->group(function () {
        Route::post('lists', [\App\Http\Controllers\Api\RankController::class, 'lists'])->name('rank.lists'); // 添加监督员
    });

    /**
     * 图形化
     */
    Route::prefix('datav')->group(function () {
        Route::post('count', [\App\Http\Controllers\Api\DataV\CountController::class, 'count']);
    });

    Route::prefix('evaluate')->group(function () {
        Route::post('dict_member_list', [\App\Http\Controllers\Api\EvaluateController::class, 'dictMemberLists']);     // 基础信息
        Route::post('config_and_detail', [\App\Http\Controllers\Api\EvaluateController::class, 'configAndDetailData']); // 表单配置
        Route::post('evaluate', [\App\Http\Controllers\Api\EvaluateController::class, 'evaluate']);                    // 评分


    });

    //});
    Route::prefix('ceping')->group(function () {
        Route::post('mems', [\App\Http\Controllers\Api\CepingController::class, 'mems']);                 // 基础信息
        // Route::post('unit', [\App\Http\Controllers\Api\CepingController::class, 'unit']);                 // 基础信息
        Route::post('add', [\App\Http\Controllers\Api\CepingController::class, 'add']);                   // 基础信息
        Route::post('check_ceping', [\App\Http\Controllers\Api\CepingController::class, 'checkCeping']);  // 基础信息

        // 民意调查相关接口
        Route::post('check_minyi_ceping', [\App\Http\Controllers\Api\CepingController::class, 'checkMinyiCeping']);      // 检测民意调查评议状态
        Route::post('add_minyi', [\App\Http\Controllers\Api\CepingController::class, 'addMinyi']);                       // 提交民意调查数据
        Route::post('up_minyi_time', [\App\Http\Controllers\Api\CepingController::class, 'up_minyi_time']);     // 修改民意评测结束时间

        Route::post('units', [\App\Http\Controllers\Api\CepingController::class, 'units']);
        Route::post('up_time', [\App\Http\Controllers\Api\CepingController::class, 'up_time']);
        Route::post('up_peos', [\App\Http\Controllers\Api\CepingController::class, 'up_peos']);
        Route::post('generate_code', [\App\Http\Controllers\Api\CepingController::class, 'generateCode']);
        Route::post('import_units', [\App\Http\Controllers\Api\CepingController::class, 'importUnits']);
    });
});
