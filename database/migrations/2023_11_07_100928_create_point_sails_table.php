<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  販売用ポイント　テーブル
| =============================================
*/
class CreatePointSailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_sails', function (Blueprint $table) {
            $table->id();
            $table->integer('value'  )->default(0);//実際付与されるポイント
            $table->integer('price'  )->default(0);//管理者編集権限
            $table->integer('service')->default(0);//サービス差異

            $table->boolean('is_subscription')->default(0);//サブスクリプションか否か
            $table->boolean('is_published')->default(1);//公開設定(利用しない->非公開*消さない)

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
        Schema::dropIfExists('point_sails');
    }
}
