<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ガチャ履歴　テーブル
| =============================================
*/
class CreateUserGachaHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_gacha_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('gacha_id')->constrained('gachas')//ガチャリレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('point_history_id')->constrained('point_histories')//ポイント収支履歴リレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->integer('play_count');//ガチャプレイ数

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
        Schema::dropIfExists('user_gacha_histories');
    }
}
