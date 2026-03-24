<?php

namespace Database\Factories;

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ManufGachaTitle;
use App\Models\Gacha;
use App\Models\Movie;
/*
| =============================================
|  Manufacturer用　ガチャタイトル ランク別動画 ファクトリー
| =============================================
*/
class ManufGachaTitleMovieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'manuf_gacha_title_id' => ManufGachaTitle::factory(),
            'gacha_id' => Gacha::factory(),
            'movie_id' => Movie::factory(),

            'gacha_rank_id' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
        ];
    }
}
