<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ユーザー取得景品　テーブル 'ticket_history_id'カラムを追加
| =============================================
*/
class AddTicketHistoryIdAndChangeGachaHistoryIdInUserPrizesTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_prizes', function (Blueprint $table) {
            // 新しいカラムの追加
            $table->integer('ticket_history_id')->nullable()->default(null)->after('shipped_id');

            // 既存のカラムの変更
            // $table->integer('gacha_history_id')->nullable()->default(null)->change();
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
            // 新しいカラムの削除
            $table->dropColumn('ticket_history_id');

            // 既存のカラムを変更するロールバック操作は提供されていません
        });
    }
}
