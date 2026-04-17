<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  Manufacturer用　ガチャタイトル ランク別動画 テーブル
| =============================================
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuf_gacha_title_movies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuf_gacha_title_id')
            ->constrained()
            ->cascadeOnDelete();

            // $table->foreignId('gacha_id')
            // ->constrained()
            // ->cascadeOnDelete();

            $table->foreignId('movie_id')
            ->constrained()
            ->cascadeOnDelete();

            $table->string('gacha_rank_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manuf_gacha_title_movies');
    }
};
