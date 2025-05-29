<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  クーポン　テーブル
| =============================================
*/
class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string( 'code'      )->nullable()->default(null);
            $table->string( 'title'     )->nullable()->default(null);
            $table->integer('prize_id'  )->nullable()->default(null);
            $table->integer('point'     )->nullable()->default(null);
            $table->integer('count'     )->default(1);
            $table->string( 'user_type' )->nullable()->default(null);
            $table->string( 'target_user_ids')->nullable()->default(null);
            $table->boolean('is_use_code')->default(0);//終了か否か
            $table->boolean('is_done'   )->default(0);//終了か否か
            $table->timestamp('published_at' )->nullable()->default(NULL);//公開日時
            $table->timestamp('expiration_at')->nullable()->default(NULL);//有効期限

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
        Schema::dropIfExists('coupons');
    }
}
