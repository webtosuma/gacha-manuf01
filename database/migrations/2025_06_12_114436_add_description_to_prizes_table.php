<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ商品　テーブル　説明文(discription)カラムの追加
| =============================================
*/
class AddDescriptionToPrizesTable extends Migration
{
    public function up()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->text('discription')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn('discription');
        });
    }
}
