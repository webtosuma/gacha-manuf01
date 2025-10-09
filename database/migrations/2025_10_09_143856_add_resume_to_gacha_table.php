<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ　テーブル　//説明文の追加
| =============================================
*/

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->string('resume')->nullable()->default(null); // 適切な位置に変更
            $table->timestamp('end_published_at' )->nullable()->default(NULL);//公開終了日時
        });
    }

    public function down(): void
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->dropColumn('resume');
        });
    }
};
