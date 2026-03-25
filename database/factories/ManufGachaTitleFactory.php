<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ManufGachaTitle;
/*
| =============================================
|  Manufacturer用　ガチャタイトル ファクトリー
| =============================================
*/
class ManufGachaTitleFactory extends Factory
{
    public function definition(): array
    {
        # ランダムな値
        $rand = rand(0, 5);

        #　タイトル名
        $names = [
            'ぽつんといちご大福 マスコットフィギュア',
            'セサミストリート フラットポーチ',
            'ケアベア ぬいぐるみバッジ',
            'クリスタルレオパ マスコットフィギュア',
            'こびとづかん3 スクエアポーチ',
            '銭湯アーミー マスコットフィギュア 銭湯イエローver.',
        ];


        return [
            'category_id'  => 1, // 固定
            'name'         => $names[$rand],
            'image_samune' => sprintf('sample/manuf/gacha_title/%02d.jpg', $rand ),
            'description'  => $this->faker->sentence(20),
            'price'        => $this->faker->numberBetween(1,18)*100,
            'code'         => ManufGachaTitle::CreateCode(),//商品コード


            'estimated_shipping_at' => $this->faker->optional()->dateTimeBetween('+1 week', '+1 month'),
            'sales_start_at'        => now(),
            'sales_end_at'          => $this->faker->dateTimeBetween('+1 week', '+2 months'),
            'published_start_at'    => now(),
            'published_end_at'      => $this->faker->dateTimeBetween('+1 week', '+2 months'),


            'set_contents'    => 'マスコットフィギュア 全5種',
            'prize_size'      => '約'. rand(0, 200).'mm',
            'prize_materials' => $this->faker->randomElement([
                'PVC製彩色マスコット','PVC','ABS','MABS',
            ]),
            'age_range'       => rand(0, 20).'才以上',
            'copy_right'      => $this->faker->optional()->company(),
        ];
    }
}
