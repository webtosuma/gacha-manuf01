<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  購入ポイント テーブル
| =============================================
*/
class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->integer('purchased_point')->default(0)->comment('購入ずみポイント');
            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除
            $table->timestamps();
            $table->softDeletes();//論理削除
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points');
    }
}
