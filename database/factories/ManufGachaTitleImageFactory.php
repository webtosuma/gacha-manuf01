<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ManufGachaTitle;
/*
| =============================================
|  Manufacturer用　ガチャタイトル画像 ファクトリー
| =============================================
*/
class ManufGachaTitleImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'manuf_gacha_title_id' => ManufGachaTitle::factory(),
            'path' => sprintf('sample/manuf/discription/%02d.png', 0 ),
        ];
    }
}
