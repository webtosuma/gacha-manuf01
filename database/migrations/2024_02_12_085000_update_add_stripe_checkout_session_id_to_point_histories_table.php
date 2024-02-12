<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ポイント収支履歴　テーブル (Stripe:Checkout Session IDカラムの追加)
| =============================================
*/
class UpdateAddStripeCheckoutSessionIdToPointHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->string('stripe_checkout_session_id')->nullable()->default(null);
            // stripe_checkout_session_id カラムを追加し、デフォルト値をNULLに設定
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->dropColumn('stripe_checkout_session_id'); // ロールバック用の処理
        });
    }
}
