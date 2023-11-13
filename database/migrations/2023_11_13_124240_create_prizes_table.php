<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  景品　テーブル
| =============================================
*/
class CreatePrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('gacha_categories')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->string('code'  )->nullable()->default(NULL);//景品コード
            $table->string('name' );//名
            $table->string('image');//画像
            $table->integer('rank_id')->nullable()->default(NULL);//ランクID
            $table->integer('point')->default(0); //交換ポイント値
            $table->dateTime('point_updated_at' );//交換ポイント値更新日時
            $table->dateTime('published_at' )->nullable()->default(NULL);//公開日時

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
        Schema::dropIfExists('prizes');
    }
}
