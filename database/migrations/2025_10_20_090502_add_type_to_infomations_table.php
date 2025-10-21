<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  お知らせ　テーブル　//お知らせの種類の追加
| =============================================
*/

return new class extends Migration {

    public function up()
    {
        Schema::table('infomations', function (Blueprint $table) {
            $table->string('type')->default('all'); // 例: お知らせの種類
        });
    }

    public function down()
    {
        Schema::table('infomations', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

};
