<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  アンケート・質問・選択肢　テーブル
| =============================================
*/
class CreateSurveyQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_question_options', function (Blueprint $table) {
            $table->id();
            $table->string('body' )->nullable()->default(null);//本文
            $table->Integer('order')->default(0);//順番

            $table->foreignId('survey_question_id')->constrained('survey_questions')
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
        Schema::dropIfExists('survey_question_options');
    }
}
