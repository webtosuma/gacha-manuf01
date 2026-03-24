<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/*
| =============================================
|  ガチャ　ファクトリー
| =============================================
*/
class GachaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id'    => 1, // 固定
            'name'           => 'SAMPLEガチャ', // 固定
            'image'          => sprintf('sample/manuf/gacha_title/%02d.jpg', 1 ),
            'key'            => \Illuminate\Support\Str::random(16),                //認証キー
            'type'           => config( 'gacha.defaults.type', 'no_custom' ) ,      //ガチャの種類
            'one_play_point' => $this->faker->randomElement([ 100,200,300,10000,]) ,//1回PLAYポイント数
            'published_at'   => now(),  //公開設定(利用しない->非公開*消さない)

            'is_meter' => config( 'gacha.defaults.is_meter', 1), //残数メーターの表示有無
            'is_slide' => config( 'gacha.defaults.is_slide', 1), //スライドの表示有無
            'min_time' => config( 'gacha.defaults.min_time', '00:00'),// 表示時間下限　2024/04/17追加
            'max_time' => config( 'gacha.defaults.max_time', '24:00'),// 表示時間上限　2024/04/17追加
        ];
    }
}
