<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャの景品　テーブル ＊特別なランク専用数 カラム追加
| =============================================
*/
return new class extends Migration {
    public function up(): void
    {
        Schema::table('gacha_prizes', function (Blueprint $table) {
            $table->integer('special_count')->nullable()->default(null);//特別なランク専用数 2024/12/23追加　//default(NULL)
        });
    }

    public function down(): void
    {
        Schema::table('gacha_prizes', function (Blueprint $table) {
            $table->dropColumn('special_count');
        });
    }
};
