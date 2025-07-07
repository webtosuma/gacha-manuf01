<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  EC 買い物カート　カラムの追加　テーブル
| =============================================
*/
class AddDoneNameToStoreKeepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_keeps', function (Blueprint $table) {
            $table->string('done_store_item_name')->nullable()->default(null);//注文時のアイテム名
            // $table->string( 'done_image_path' )->nullable()->default(null);//注文時のアイテム画像パス
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_keeps', function (Blueprint $table) {
            $table->dropColumn('done_store_item_name');
        });
    }
}
