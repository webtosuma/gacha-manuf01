<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  point_histories テーブル インデックスの追加
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {

            // 単体インデックス
            $table->index('created_at');

            // 複合インデックス
            $table->index(['user_id', 'created_at']);
            $table->index(['reason_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {

            // $table->dropIndex(['created_at']);
            // $table->dropIndex(['user_id', 'created_at']);
            // $table->dropIndex(['reason_id', 'created_at']);
        });
    }
};
