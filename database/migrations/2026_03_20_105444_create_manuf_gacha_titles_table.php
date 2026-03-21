<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  Manufacturer用　ガチャタイトル テーブル
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuf_gacha_titles', function (Blueprint $table) {
            $table->id();

            // リレーション
            $table->foreignId('category_id')
            ->constrained('gacha_categories')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            // 基本情報
            $table->string('name');               //名称
            $table->string('image_samune');       //サムネ画像
            $table->integer('price')->default(0); //価格(税込み)

            // 日時系
            $table->dateTime('estimated_shipping_at')->nullable()->default(null);//発送予定日時
            $table->dateTime('sales_start_at'       )->nullable()->default(null);//販売開始日時
            $table->dateTime('sales_end_at'         )->nullable()->default(null);//販売終了日時
            $table->dateTime('published_start_at'   )->nullable()->default(null);//公開開始日時
            $table->dateTime('published_end_at'     )->nullable()->default(null);//公開終了日時

            // 詳細情報
            $table->string('description'    )->nullable()->default(null);//説明文
            $table->string('set_contents'   )->nullable()->default(null);//セット内容
            $table->string('prize_size'     )->nullable()->default(null);//商品サイズ
            $table->string('prize_materials')->nullable()->default(null);//商品素材
            $table->string('age_range'      )->nullable()->default(null);//対象年齢
            $table->string('copy_right'     )->nullable()->default(null);//コピーライト

            $table->softDeletes();//論理削除
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manuf_gacha_titles');
    }
};
