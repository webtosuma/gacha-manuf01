<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  Manufacturer用　ガチャタイトル商品 テーブル
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuf_gacha_title_prizes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuf_gacha_title_id')
            ->constrained()
            ->cascadeOnDelete();

            $table->foreignId('prize_id')
            ->constrained()
            ->cascadeOnDelete();

            $table->integer('order')->default(0);//並び順
            $table->dateTime('published_at')->nullable();//公開日時

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manuf_gacha_title_prizes');
    }
};
