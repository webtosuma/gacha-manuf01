<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャのカテゴリーグループ　テーブル
| =============================================
*/
class CreateGachaCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gacha_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'     );//'カテゴリー名'
            $table->string('code_name');//'コードネーム（ルーティング用）'
            $table->string('bg_image' );//'背景画像'
            $table->boolean('is_published' )->default(1);//公開

            $table->softDeletes();//論理削除
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
        Schema::dropIfExists('gacha_categories');
    }
}
