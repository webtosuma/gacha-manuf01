<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  買取表　テーブル
| =============================================
*/
class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('prize_id'  );
            $table->integer('price'  )->default(0);
            $table->timestamp('published_at' )->nullable()->default(NULL);//公開日時
            $table->timestamp('done_at')->nullable()->default(NULL);//買取終了
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
        Schema::dropIfExists('purchases');
    }
}
