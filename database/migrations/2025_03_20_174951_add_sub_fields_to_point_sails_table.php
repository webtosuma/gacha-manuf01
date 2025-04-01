<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  販売用ポイント サブスク用カラム追加　テーブル
| =============================================
*/
return new class extends Migration {
    public function up(): void
    {
        Schema::table('point_sails', function (Blueprint $table) {
            $table->string('sub_image'        )->nullable()->default(null);
            $table->string('sub_description'  )->nullable()->default(null);
            $table->string('sub_label'        )->nullable()->default(null);
            $table->string('sub_billing_cycle')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('point_sails', function (Blueprint $table) {
            $table->dropColumn(['sub_image', 'sub_description', 'sub_label','sub_billing_cycle']);
        });
    }
};
