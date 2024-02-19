<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  お知らせ メール送信済み　テーブル
| =============================================
*/
class CreateInfomationSentEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infomation_sent_emails', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('infomation_id')->constrained('infomations')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infomation_sent_emails');
    }
}
