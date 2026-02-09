<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  Gachas テーブル 限定回数　カラムの追加
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->integer('type_n_count')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->dropColumn('type_n_count');
        });
    }
};
