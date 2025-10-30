<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  買取カテゴリー　テーブル
| =============================================
*/
class CreatePurchaseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'     );//'カテゴリー名'
            $table->boolean('is_published' )->default(1);//公開
            $table->integer('order')->default(0); // 位置は適宜調整
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
        Schema::dropIfExists('purchase_categories');
    }
}
