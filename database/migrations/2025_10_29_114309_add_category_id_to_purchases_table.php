<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  買取　テーブル　'category_id'カラム追加
| =============================================
*/
return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
};
