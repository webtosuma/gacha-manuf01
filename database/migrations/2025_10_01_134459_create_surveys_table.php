<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  アンケート　テーブル
| =============================================
*/
class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('code'    )->nullable()->default(NULL); // コード
            $table->string('title'   )->nullable()->default(NULL); // タイトル
            $table->string('resume'  )->nullable()->default(NULL); // 説明文
            $table->integer('gacha_id')->nullable()->default(NULL);
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
        Schema::dropIfExists('surveys');
    }
}
