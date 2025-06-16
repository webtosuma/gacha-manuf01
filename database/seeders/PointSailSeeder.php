<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/*
| =============================================
|  販売用ポイント登録　シーダー
| =============================================
*/
class PointSailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datalist = self::dataList();

        foreach ($datalist as $data)
        {
            $pointSail = new \App\Models\PointSail($data);
            $pointSail->save();
        }
    }





    /**
     * サイト管理者情報
     *
     * @return Array
     */
    public function dataList()
    {
        return   [
            [
                'value'   => 1000,  //実際付与されるポイント
                'price'   => 1000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'price_1OXZ2OKoJdkajOL0BOK0AkIi',
            ],
            [
                'value'   => 3000,  //実際付与されるポイント
                'price'   => 3000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'price_1OXZ2zKoJdkajOL0eOBChesf',
            ],
            [
                'value'   => 5000,  //実際付与されるポイント
                'price'   => 5000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'price_1OXZ3YKoJdkajOL0Xuh40BWc',
            ],
            [
                'value'   => 10000,  //実際付与されるポイント
                'price'   => 10000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'price_1OXZ4GKoJdkajOL0Rhb98kYK',
            ],
            [
                'value'   => 50000,  //実際付与されるポイント
                'price'   => 50000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'price_1OXZ55KoJdkajOL042ykDRjf',
            ],

        ];

    }
}
