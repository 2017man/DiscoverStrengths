<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminTable extends Migration
{
    public function getConnection()
    {
        return $this->config('database.connection') ?: config('database.default');
    }

    public function config($key)
    {
        return config('admin.' . $key);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->config('database.users_table'))) {
            Schema::table($this->config('database.users_table'), function (Blueprint $table) {
                $table->string('map_area', 50)->default('')->nullable()->comment('支撑区县');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->config('database.users_table'), function (Blueprint $table) {
            $table->dropColumn('map_area');
        });
    }
}
