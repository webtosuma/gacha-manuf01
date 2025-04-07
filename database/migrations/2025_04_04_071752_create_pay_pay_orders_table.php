<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  PayPay注文情報(ポイント購入待機)　テーブル
| =============================================
*/
class CreatePayPayOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_pay_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');      //ユーザーID
            $table->integer('point_sail_id');//販売ポイントID
            $table->integer('point_history_id')->nullable()->default(NULL);//ポイント履歴ID
            $table->string('paypay_merchant_payment_id');//PayPay決済ID
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
        Schema::dropIfExists('pay_pay_orders');
    }
}
