<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  スポンサー　広告 テーブル
| =============================================
*/
class CreateSponsorAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_ads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->default(null);
            $table->string('url'  )->nullable()->default(null);
            $table->string('movie')->nullable()->default(null);
            $table->string('memo' )->nullable()->default(null);
            $table->integer('plan_id')->nullable()->default(NULL);
            $table->string('gacha_id')->nullable()->default(null);
            $table->integer('access_count')->default(0);

            $table->foreignId('sponsor_id')->constrained('sponsors')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

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
        Schema::dropIfExists('sponsor_ads');
    }
}
