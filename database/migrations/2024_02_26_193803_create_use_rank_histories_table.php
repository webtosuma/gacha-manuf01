<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  会員ランク履歴　テーブル
| =============================================
*/
class CreateUseRankHistoriesTable extends Migration
{
    /**
     * Run the migrations.　
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_rank_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rank_id');
            $table->integer('pt_count')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('use_rank_histories');
    }
}
