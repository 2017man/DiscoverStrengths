<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * 为 strengths_site_config 插入初始记录（若表为空）
 */
class InsertStrengthsSiteConfigInitial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('strengths_site_config')) {
            return;
        }
        $exists = DB::table('strengths_site_config')->exists();
        if (!$exists) {
            DB::table('strengths_site_config')->insert([
                'stats_count' => '0',
                'stats_date' => '2014年5月19日 ~ 至今',
                'qrcode_wechat' => null,
                'qrcode_community' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 不删除数据，避免误删
    }
}
