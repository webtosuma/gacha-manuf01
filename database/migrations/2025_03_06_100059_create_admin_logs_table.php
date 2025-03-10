<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  Admin 操作ログ　テーブル
| =============================================
*/
class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')->constrained('admins')
            ->onDelete('cascade');//主テーブルに関連する従テーブルのレコードを削除

            $table->string('type_id'  )->nullable()->default(NULL); // 履歴の種類
            $table->string('target_id')->nullable()->default(NULL); // 履歴に関係するデータの紐付け

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
        Schema::dropIfExists('admin_logs');
    }
}
