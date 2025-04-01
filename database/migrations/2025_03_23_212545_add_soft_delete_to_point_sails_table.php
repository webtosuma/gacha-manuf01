<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  販売用ポイント　テーブル　soft_deleteカラム追加(2025/03/23)
| =============================================
*/
return new class extends Migration {
    public function up(): void
    {
        Schema::table('point_sails', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('point_sails', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
