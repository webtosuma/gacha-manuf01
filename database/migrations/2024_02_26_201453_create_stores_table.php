<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  販売商品　テーブル
| =============================================
*/
class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prize_id')->constrained('prizes');
            $table->foreignId('category_id')->constrained('gacha_categories');
            $table->foreignId('user_id')->constrained('users')->default(1);

            $table->integer('ticket_count'  )->nullable()->default(null);
            $table->integer('point_count'   )->nullable()->default(null);
            $table->timestamp('published_at')->nullable()->default(null);
            $table->integer('count')->default(0);
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
        Schema::dropIfExists('stores');
    }
}
