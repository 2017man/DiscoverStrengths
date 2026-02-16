<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEpayOrderIdToStrengthsOrders extends Migration
{
    /**
     * 易支付云端订单号，用于查询订单信息/查询订单状态（getOrder、checkOrder）
     */
    public function up()
    {
        Schema::table('strengths_orders', function (Blueprint $table) {
            $table->string('epay_order_id', 64)->nullable()->after('out_trade_no')->comment('易支付云端订单号，用于 getOrder/checkOrder');
        });
    }

    public function down()
    {
        Schema::table('strengths_orders', function (Blueprint $table) {
            $table->dropColumn('epay_order_id');
        });
    }
}
