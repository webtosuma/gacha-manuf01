<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  [キャンペーン]　お友達紹介　テーブル
| =============================================
*/
class CreateCanpaingIntroductoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canpaing_introductories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recruiter_id')->constrained('users')//勧誘ユーザーのID
            ->onDelete('cascade');

            $table->foreignId('friend_id')->constrained('users')//紹介した友達のID
            ->onDelete('cascade');

            $table->timestamp('done_at' )->nullable()->default(NULL);//キャンペーン付与日

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
        Schema::dropIfExists('canpaing_introductories');
    }
}
