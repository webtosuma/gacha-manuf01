<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  EC 販売アイテム　テーブル
| =============================================
*/
class CreateStoreItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_items', function (Blueprint $table) {
            $table->id();
            $table->string( 'code' );//商品コード
            // $table->foreignId('category_id')->constrained('gacha_categories')
            // ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除
            $table->integer('category_id'      )->nullable()->default(null);
            $table->integer('prize_id'         )->nullable()->default(null);

            $table->string( 'item_name'        )->nullable()->default(null);
            $table->string( 'images'           )->nullable()->default(null);
            $table->string( 'movie'            )->nullable()->default(null);
            $table->string( 'brand_name'       )->nullable()->default(null);
            $table->string( 'discription'      )->nullable()->default(null);

            $table->integer('price'            )->default(0);//販売価格
            $table->integer('count'            )->default(0);//在庫数
            $table->integer('points_redemption')->default(0);//還元ポイント

            $table->boolean('is_slide'         )->default(0);//スライド表示
            $table->timestamp('published_at'    )->nullable()->default(NULL);//公開日時
            $table->timestamp('back_in_stock_at')->nullable()->default(NULL);//再入荷日時

            $table->integer('purchased_count'  )->default(0);//購入された回数
            $table->integer('showed_count'     )->default(0);//表示した回数

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
        Schema::dropIfExists('store_items');
    }
}
