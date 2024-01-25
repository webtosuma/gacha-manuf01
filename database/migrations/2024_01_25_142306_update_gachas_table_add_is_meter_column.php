<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ テーブル (カラムの追加)
| =============================================
*/
class UpdateGachasTableAddIsMeterColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->boolean('is_meter')->default(1);//残数メーターの表示有無
            $table->boolean('is_slide')->default(1);//スライドの表示有無
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
            $table->dropColumn('is_meter');
            $table->dropColumn('is_slide');
        });
    }
}
