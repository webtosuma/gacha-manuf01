<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  アンケート・質問　テーブル
| =============================================
*/
class CreateSurveyQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');//題名
            $table->string('body' )->nullable()->default(null);//本文
            $table->string('image')->nullable()->default(null);//画像
            $table->string('type');//質問の種類
            $table->Integer('order')->default(0);//順番

            $table->foreignId('survey_id')->constrained('surveys')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除
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
        Schema::dropIfExists('survey_questions');
    }
}
