<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャの景品　テーブル
| =============================================
*/
class CreateGachaPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gacha_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gacha_id')->constrained('gachas')//ガチャリレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('prize_id')->constrained('prizes')//景品リレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->string('gacha_rank_id');//ランクID
            $table->integer('max_count')->default(0);       //景品総数
            $table->integer('remaining_count')->default(0); //景品残数
            $table->integer('win_order')->nullable()->default(NULL); //指定して当選する順番

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
        Schema::dropIfExists('gacha_prizes');
    }
}
