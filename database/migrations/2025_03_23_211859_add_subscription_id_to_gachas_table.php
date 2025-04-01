<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ　テーブル　subscription_idカラム追加(2025/03/23)
| =============================================
*/
return new class extends Migration {
    public function up(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->dropColumn('subscription_id');
        });
    }
};
