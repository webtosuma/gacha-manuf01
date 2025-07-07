<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  EC 買い物カート　テーブル
| =============================================
*/
class CreateStoreKeepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_keeps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'      )->constrained()->onDelete('cascade');//ユーザー　　　リレーション
            $table->foreignId('store_item_id')->constrained()->onDelete('cascade');//販売アイテム　リレーション
            $table->integer('count'          )->default(0);//注文数
            $table->boolean('is_buy_now'     )->default(0);//今すぐ購入か否か(カート非表示)

            /*チェックアウト時、登録 */
            $table->integer('store_history_id')->nullable()->default(null);//販売履歴ID　　　リレーション
            $table->integer('done_sum_price'            )->default(0);//注文時の販売価格
            $table->integer('done_sum_points_redemption')->default(0);//注文時の還元ポイント

            /* 決済完了後登録 */
            $table->timestamp('done_at')->nullable()->default(NULL);       //決済完了日時
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
        Schema::dropIfExists('store_keeps');
    }
}
