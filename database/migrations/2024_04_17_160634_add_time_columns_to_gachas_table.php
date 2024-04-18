<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ　テーブル 表示可能時間カラム　2024/04/17追加
| =============================================
*/
class AddTimeColumnsToGachasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->string('min_time')->default('00:00');
            $table->string('max_time')->default('24:00');
            $table->boolean('is_over_date')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->dropColumn('min_time');
            $table->dropColumn('max_time');
        });
    }
}
