<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  EC 商品の検索履歴　テーブル
| =============================================
*/
class CreateStoreSearchHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_search_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');//ユーザー　　　リレーション
            $table->string( 'keyword')->nullable()->default(null);//入力値
            $table->integer('count')->default(1);//ユーザーの利用回数
            $table->timestamp('done_at')->nullable()->default(NULL);//履歴非表示の指定日時

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
        Schema::dropIfExists('store_search_histories');
    }
}
