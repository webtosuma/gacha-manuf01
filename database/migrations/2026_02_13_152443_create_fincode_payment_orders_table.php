<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  fincode 仮注文 テーブル
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fincode_payment_orders', function (Blueprint $table) {
            $table->id();

            # ユーザー
            $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

            # 購入対象ポイント
            $table->foreignId('point_sail_id')
            ->constrained()
            ->cascadeOnDelete();

            # fincodeのtransaction_id
            $table->string('fincode_transaction_id')
            ->nullable()
            ->unique();

            # ステータス
            /**
             * pending:   決済待ち*
             * completed: 決済完了
             * failed:    失敗
             */
            $table->string('status')
            ->default('pending')
            ->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fincode_payment_orders');
    }
};
