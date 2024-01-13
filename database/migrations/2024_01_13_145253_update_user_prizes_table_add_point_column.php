<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ユーザー取得景品　テーブル (pointカラムの追加)
| =============================================
*/
class UpdateUserPrizesTableAddPointColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_prizes', function (Blueprint $table) {
            $table->integer('point')->default(1); //交換ポイント値
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_prizes', function (Blueprint $table) {
            $table->doropColumn(('point'));
        });
    }
}
