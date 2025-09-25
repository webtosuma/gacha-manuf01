<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  User　テーブル 二段階認証カラム(tfa_key)の追加 
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string(   'tfa_key')->nullable()->default(null);
            $table->Integer(  'tfa_failures_count')->default(0);
            $table->timestamp('tfa_failures_at'   )->nullable()->default(NULL);
            $table->boolean('is_tfa')->default(0);//二段階認証を利用するか否か
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tfa_key');
        });
    }
};
