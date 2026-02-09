<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  user_gacha_histories テーブル 'movie_id'　カラムの追加
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_gacha_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('user_gacha_histories', function (Blueprint $table) {
            $table->dropColumn('movie_id');
        });
    }
};
