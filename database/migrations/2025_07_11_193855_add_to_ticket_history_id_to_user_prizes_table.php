<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ユーザー取得商品　テーブル to_ticket_history_idカラムの追加
| =============================================
*/
class AddToTicketHistoryIdToUserPrizesTable extends Migration
{
    public function up()
    {
        Schema::table('user_prizes', function (Blueprint $table) {
            $table->integer('to_ticket_history_id')->nullable()->default(null)
            ->after('ticket_history_id');
        });
    }

    public function down()
    {
        Schema::table('user_prizes', function (Blueprint $table) {
            $table->dropColumn('to_ticket_history_id');
        });
    }

}
