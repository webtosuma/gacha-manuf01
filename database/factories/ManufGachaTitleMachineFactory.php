<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ManufGachaTitle;
use App\Models\Gacha;
/*
| =============================================
|  Manufacturer用　ガチャタイトル筐体 ファクトリー
| =============================================
*/
class ManufGachaTitleMachineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'manuf_gacha_title_id' => ManufGachaTitle::factory(),
            'gacha_id' => Gacha::factory(),
        ];
    }
}
