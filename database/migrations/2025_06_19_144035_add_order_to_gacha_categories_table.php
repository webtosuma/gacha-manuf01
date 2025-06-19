<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャのカテゴリーグループ　テーブル orderカラムの追加
| =============================================
*/
class AddOrderToGachaCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gacha_categories', function (Blueprint $table) {
            $table->integer('order')->default(0); // 位置は適宜調整
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gacha_categories', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
