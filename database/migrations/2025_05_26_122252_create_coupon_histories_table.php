<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  クーポン履歴　テーブル
| =============================================
*/
class CreateCouponHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'  )->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->integer('point_history_id')->nullable()->default(null);
            $table->integer('user_prize_id'   )->nullable()->default(null);
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
        Schema::dropIfExists('coupon_histories');
    }
}
