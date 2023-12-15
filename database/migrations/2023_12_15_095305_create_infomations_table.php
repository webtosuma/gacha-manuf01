<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  お知らせ　テーブル
| =============================================
*/
class CreateInfomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infomations', function (Blueprint $table) {
            $table->id();
            $table->string('title');//題名
            $table->string('body' )->nullable()->default(null);//本文
            $table->string('image')->nullable()->default(null);//画像
            $table->boolean('is_slide')->default(0);//スライドの表示有無
            $table->timestamp('published_at' )->nullable()->default(NULL);//公開日時
            $table->Integer('user_id')->nullable()->default(null);//特定のユーザーに送信か、否か

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
        Schema::dropIfExists('infomations');
    }
}
