<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  Manufacturer用　購入履歴 テーブル
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuf_purchase_histories', function (Blueprint $table) {
            $table->id();

            # 履歴コード
            $table->string('code')
                ->unique()
                ->comment('履歴コード');

            # ユーザー
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            # ユーザーアドレスID(保存用) 
            $table->integer('address_id');

            # 発送料金
            $table->integer('shipped_fee')
                ->default(0)
                ->comment('発送料金');

            # 状態
            $table->string('status')
                ->default('pending')
                ->comment('pending:購入待ち paid:支払い済み cancel:キャンセル');

            # 発送情報
            $table->foreignId('shipped_id')
                ->nullable()
                ->default(null)
                ->constrained('user_shippeds')
                ->nullOnDelete();

            # 支払い完了日時
            $table->timestamp('paid_at')
                ->nullable();

            # Stripe Checkout Session ID
            $table->string('stripe_checkout_session_id')
                ->nullable()
                ->default(null);

            $table->timestamps();

            # 論理削除
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuf_purchase_histories');
    }
};