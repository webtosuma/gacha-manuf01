<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  販売用ポイント　テーブル (stripe_idカラム追加)
| =============================================
*/
class UpdateAddStripeIdToPointSailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_sails', function (Blueprint $table) {
            $table->string('stripe_id');//Stipeの商品ID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_sails', function (Blueprint $table) {
            $table->doropColumn(('stripe_id'));
        });
    }
}
