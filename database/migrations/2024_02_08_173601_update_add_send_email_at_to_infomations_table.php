<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  お知らせ　テーブル (メール送信日時　カラムの追加)
| =============================================
*/
class UpdateAddSendEmailAtToInfomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infomations', function (Blueprint $table) {
            $table->dateTime('send_email_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infomations', function (Blueprint $table) {
            $table->dropColumn('send_email_at'); // 追加したカラムの削除
        });
    }
}
