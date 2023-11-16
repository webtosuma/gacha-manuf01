<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  ユーザーアドレス テーブル
| =============================================
*/
class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name'       )->nullable()->default(NULL);//宛名
            $table->string('tell'       )->nullable()->default(NULL);//電話番号

            $table->string('postal_code')->nullable()->default(NULL);//'郵便番号'
            $table->string('todohuken'  )->nullable()->default(NULL);//'住所-都道府県'
            $table->string('shikuchoson')->nullable()->default(NULL);//'住所-市町村'
            $table->string('number'     )->nullable()->default(NULL);//'住所-番地'
            $table->boolean('is_default')->default(0);//デフォルトの送信先か否か

            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除
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
        Schema::dropIfExists('user_addresses');
    }
}
