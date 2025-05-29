<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ユーザー取得景品　テーブル　gacha_history_idカラムの型変更(2025/05/26)
| =============================================
*/
class ChangeGachaHistoryIdTypeInUserPrizesTable extends Migration
{
    public function up()
    {
        // 外部キー制約を削除
        Schema::table('user_prizes', function ($table) {
            $table->dropForeign(['gacha_history_id']);
        });

        // カラムの型を整数型に変更
        DB::statement('ALTER TABLE user_prizes MODIFY gacha_history_id INT');

        // ※ 外部キーは戻さない（整数型では外部キーにできないため）
    }

    public function down()
    {
        // 元の型（unsignedBigInteger）に戻す
        DB::statement('ALTER TABLE user_prizes MODIFY gacha_history_id BIGINT UNSIGNED');

        // 外部キー制約を復元
        Schema::table('user_prizes', function ($table) {
            $table->foreign('gacha_history_id')
                ->references('id')->on('user_gacha_histories')
                ->onDelete('cascade');
        });
    }
}
