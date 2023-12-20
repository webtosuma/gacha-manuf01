<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  景品発送履歴　テーブル
| =============================================
*/
class CreateUserShippedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shippeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('user_address_id')->constrained('user_addresses')//ユーザーアドレス
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->foreignId('point_history_id')->constrained('point_histories')//ポイント収支履歴リレーション
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->integer('state_id');//発送状況
            $table->timestamp('shipment_at')->nullable()->default(NULL);//発送日時
            $table->boolean('shipment_read')->default(0);//ユーザーの発送確認
            $table->timestamp('arrival_at' )->nullable()->default(NULL);//到着日時

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
        Schema::dropIfExists('user_shippeds');
    }
}
