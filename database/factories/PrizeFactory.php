<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Prize;
/*
| =============================================
|  商品　ファクトリー
| =============================================
*/
class PrizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => 1, // 固定
            'code'        => Prize::CreateCode(),//商品コード
            'name'        => 'テスト商品',
            'image'       => sprintf('sample/manuf/gacha_title/%02d.jpg', 1 ),
            'rank_id'     => 3,//'a'
            'published_at'     => now(),
            'point_updated_at' => now(),

        ];
    }
}
