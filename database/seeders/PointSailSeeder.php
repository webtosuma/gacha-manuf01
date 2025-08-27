<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PointSail;
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
            $data['stripe_id'] = PointSail::CreateCode();
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
                'value'   => 500,  //実際付与されるポイント
                'price'   => 500,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'',
            ],
            [
                'value'   => 1*1000,  //実際付与されるポイント
                'price'   => 1*1000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'',
            ],
            // [
            //     'value'   => 3*1000,  //実際付与されるポイント
            //     'price'   => 3*1000,  //支払い金額
            //     'service' => 0,//サービス差異
            //     'stripe_id'=>'',
            // ],
            [
                'value'   => 5*1000,  //実際付与されるポイント
                'price'   => 5*1000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'',
            ],
            [
                'value'   => 10*1000,  //実際付与されるポイント
                'price'   => 10*1000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'',
            ],
            [
                'value'   => 50*1000,  //実際付与されるポイント
                'price'   => 50*1000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'',
            ],
            [
                'value'   => 100*1000,  //実際付与されるポイント
                'price'   => 10*1000,  //支払い金額
                'service' => 0,//サービス差異
                'stripe_id'=>'',
            ],

        ];

    }
}
