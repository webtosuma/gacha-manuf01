<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  Manufacturer用　購入アイテム テーブル
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuf_purchase_items', function (Blueprint $table) {
            $table->id();

            # ユーザー
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            # ガチャマシーン
            $table->foreignId('machine_id')
                ->constrained('manuf_gacha_title_machines')
                ->cascadeOnDelete();

            # 購入履歴
            $table->foreignId('history_id')
                ->constrained('manuf_purchase_histories')
                ->cascadeOnDelete();

            # ガチャ利用回数
            $table->integer('count')
                ->default(1)
                ->comment('ガチャを回す回数');

            # 1回あたりの料金
            $table->integer('one_fee')
                ->default(0)
                ->comment('ガチャ1回あたりの料金');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuf_purchase_items');
    }
};