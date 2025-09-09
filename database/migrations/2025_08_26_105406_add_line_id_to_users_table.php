<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  User　テーブル SNSログイン用カラムの追加
| =============================================
*/
class AddLineIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('line_id'    )->nullable()->unique();//[snsログイン]LINE 　2025/8/26追加
            // $table->string('google_id'  )->nullable()->unique();//[snsログイン]Google 2025/8/26追加
            // $table->string('yahoo_id'   )->nullable()->unique();//snsログイン]Yahoo   2025/8/26追加
            $table->string('facebook_id')->nullable()->unique();//snsログイン]facebook2025/8/26追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('line_id');
            // $table->dropColumn('google_id');
            // $table->dropColumn('yahoo_id');
            $table->dropColumn('facebook_id');
        });
    }
}
