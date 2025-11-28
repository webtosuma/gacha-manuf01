<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  UserSeeped テーブル 追跡コード　カラムの追加
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_shippeds', function (Blueprint $table) {
            $table->string('shipping_company_id')->nullable()->default(null);
            $table->string('tracking_code'      )->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('user_shippeds', function (Blueprint $table) {
            $table->dropColumn(['shipping_company_id', 'tracking_code']);
        });
    }
};
