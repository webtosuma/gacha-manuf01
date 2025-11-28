<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  User　テーブル current_session_idカラムの追加　//1アカウント1ログイン
| =============================================
*/
class AddCurrentSessionIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // current_session_id を追加（NULL 許可）
            $table->string('current_session_id')->nullable()->after('remember_token');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('current_session_id');
        });
    }
}
