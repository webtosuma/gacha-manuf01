<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  スポンサー　広告 テーブル　カラム追加
| =============================================
*/
class AddMoviePlayCountToSponsorAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sponsor_ads', function (Blueprint $table) {
            $table->integer('movie_play_count')->default(0); // movie_play_count カラムを追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sponsor_ads', function (Blueprint $table) {
            $table->dropColumn('movie_play_count'); // movie_play_count カラムを削除
        });
    }
}
