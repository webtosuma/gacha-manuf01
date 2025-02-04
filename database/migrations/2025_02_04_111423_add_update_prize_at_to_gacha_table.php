<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ テーブル ＊登録商品更新日時 カラム追加
| =============================================
*/
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->dateTime('updated_prizes_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->dropColumn('updated_prizes_at');
        });
    }
};
