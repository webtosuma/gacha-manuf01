<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ　テーブル
| =============================================
*/
class CreateGachasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gachas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('gacha_categories')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->string('name' );//ガチャ名
            $table->string('image');//画像
            $table->integer('one_play_point')->default(0);//1回PLAYポイント数
            $table->integer('ten_play_point')->default(0);//10回PLAYポイント数
            $table->timestamp('published_at' )->nullable()->default(NULL);//公開日時
            $table->string('key');//'認証キー'
            $table->string('type')->default('nomal');//ガチャのタイプ

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
        Schema::dropIfExists('gachas');
    }
}
