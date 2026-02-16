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
     * 发现你的天赋优势 - 测试类型 / MBTI / 订单
     */
    Route::get('test-types/list', [\App\Http\Controllers\Api\TestTypeController::class, 'list'])->name('test_types.list');
    Route::get('test-types/detail', [\App\Http\Controllers\Api\TestTypeController::class, 'detail'])->name('test_types.detail');
    Route::get('mbti/intro', [\App\Http\Controllers\Api\MbtiController::class, 'intro'])->name('mbti.intro');
    Route::get('mbti/questions', [\App\Http\Controllers\Api\MbtiController::class, 'questions'])->name('mbti.questions');
    Route::post('mbti/submit', [\App\Http\Controllers\Api\MbtiController::class, 'submit'])->name('mbti.submit');
    Route::get('mbti/report', [\App\Http\Controllers\Api\MbtiController::class, 'report'])->name('mbti.report');
    Route::get('mbti/report/pdf', [\App\Http\Controllers\Api\MbtiController::class, 'reportPdf'])->name('mbti.report.pdf');
    Route::post('order/create', [\App\Http\Controllers\Api\OrderController::class, 'create'])->name('order.create');
    Route::get('order/status', [\App\Http\Controllers\Api\OrderController::class, 'status'])->name('order.status');
    Route::get('order/check-payment', [\App\Http\Controllers\Api\OrderController::class, 'checkPayment'])->name('order.check_payment');

    /**
     * 支付回调（易支付异步通知，文档为 GET 请求，不校验登录）
     */
    Route::get('payment/epay/notify', [\App\Http\Controllers\Api\PaymentController::class, 'epayNotify'])->name('payment.epay.notify');
    Route::get('payment/epay/placeholder', [\App\Http\Controllers\Api\PaymentController::class, 'epayPlaceholder'])->name('payment.epay.placeholder');

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
        Route::get('site', [\App\Http\Controllers\Api\ConfigController::class, 'site'])->name('config.site'); // 站点配置（首页 stats、二维码）
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
});
