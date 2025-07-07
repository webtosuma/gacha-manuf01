<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  EC 注文履歴　テーブル
| =============================================
*/
class CreateStoreHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_histories', function (Blueprint $table) {
            $table->id();
            $table->string( 'code' );//履歴コード

            $table->foreignId('user_id')->constrained()->onDelete('cascade');//ユーザーリレーション
            $table->integer('user_address_id' )->nullable()->default(null); //発送先アドレス(保存用)
            $table->integer('use_point'       )->default(0);//利用ポイント(保存用)
            $table->integer('redemption_point')->default(0);//還元ポイント(保存用)
            $table->integer('shipped_price'   )->default(0);//発送料金

            /* 決済完了後登録 */
            $table->timestamp('done_at'                  )->nullable()->default(NULL);//決済完了日時
            $table->string( 'stripe_checkout_session_id' )->nullable()->default(null);//Stripe決済完了ID
            $table->integer('use_point_history_id'       )->nullable()->default(null);//利用ポイント履歴ID
            $table->integer('redemption_point_history_id')->nullable()->default(null);//還元ポイント履歴ID

            $table->integer('state_id')->nullable()->default(NULL);//発送状況
            $table->timestamp('shipment_at')->nullable()->default(NULL);//発送日時
            $table->boolean('shipment_read')->default(0);//ユーザーの発送確認
            $table->timestamp('arrival_at' )->nullable()->default(NULL);//到着日時

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
        Schema::dropIfExists('store_histories');
    }
}
