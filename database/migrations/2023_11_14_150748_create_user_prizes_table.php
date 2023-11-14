<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ユーザー取得景品　テーブル
| =============================================
*/
class CreateUserPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('prize_id')->constrained('prizes')//景品リレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('gacha_history_id')->constrained('user_gacha_histories')//ガチャ履歴
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->integer('point_history_id')->nullable()->default(NULL);
            //ポイント収支履歴リレーション（ポイント交換した時のみ）

            $table->integer('shipped_id')->nullable()->default(NULL);
            //発送履歴（発送した時のみ）

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
        Schema::dropIfExists('user_prizes');
    }
}
