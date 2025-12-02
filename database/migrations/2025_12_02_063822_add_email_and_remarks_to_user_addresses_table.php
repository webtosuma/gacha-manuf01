<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  アクセスログ　テーブル
| =============================================
*/
class AddEmailAndRemarksToUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            // 必要に応じて長さや nullable を調整してください
            $table->string('email')->nullable()->dafault(null);
            $table->string('remarks')->nullable()->dafault(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn(['email', 'remarks']);
        });
    }
}
