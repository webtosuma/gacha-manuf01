<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ManufGachaTitle;
use App\Models\Prize;
/*
| =============================================
|  Manufacturer用　ガチャタイトル商品 ファクトリー
| =============================================
*/
class ManufGachaTitlePrizeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'manuf_gacha_title_id' => ManufGachaTitle::factory(),
            'prize_id' => Prize::factory(),
            'order'    => 1,
            'published_at' => now(),
        ];
    }
}
