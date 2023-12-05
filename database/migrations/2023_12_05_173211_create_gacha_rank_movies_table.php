<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ・ランク別　演出動画　テーブル
| =============================================
*/
class CreateGachaRankMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gacha_rank_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gacha_id')->constrained('gachas')//ガチャリレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('movie_id')->constrained('movies')//演出動画リレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->string('gacha_rank_id');//ランクID
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
        Schema::dropIfExists('gacha_rank_movies');
    }
}
