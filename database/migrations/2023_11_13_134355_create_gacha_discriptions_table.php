<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャの詳細説明情報　テーブル
| =============================================
*/
class CreateGachaDiscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gacha_discriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gacha_id')->constrained('gachas')//ガチャリレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除
            $table->string('image');//画像
            $table->string('sorce')->nullable()->default(NULL);//説明文
            $table->integer('rank_id');//ランクID

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
        Schema::dropIfExists('gacha_discriptions');
    }
}
