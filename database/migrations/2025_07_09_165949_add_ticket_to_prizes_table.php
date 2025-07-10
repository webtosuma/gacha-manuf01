<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  商品　テーブル ticketカラムの追加
| =============================================
*/
class AddTicketToPrizesTable extends Migration
{
    public function up()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->integer('ticket')->default(0)->after('point');
        });
    }

    public function down()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn('ticket');
        });
    }
}
