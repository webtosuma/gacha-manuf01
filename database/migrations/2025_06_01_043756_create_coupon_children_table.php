<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  クーポン (1回限定クーポンコード)　テーブル
| =============================================
*/
class CreateCouponChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_children', function (Blueprint $table) {
            $table->id();
            $table->string( 'code'   )->nullable()->default(null);
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->integer('user_id')->nullable()->default(null);
            $table->boolean('is_done')->default(0);//終了か否か
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
        Schema::dropIfExists('coupon_children');
    }
}
