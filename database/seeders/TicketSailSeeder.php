<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/*
| =============================================
|  販売用チケット　シーダー
| =============================================
*/
class TicketSailSeeder extends Seeder
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
            $ticketSail = new \App\Models\TicketSail($data);
            $ticketSail->save();
        }
    }



    /**
     * DATS
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
                'value'   => 3100,  //実際付与されるポイント
                'price'   => 3000,  //支払い金額
                'service' => 100,//サービス差異
                'stripe_id'=>'price_1OXZ2zKoJdkajOL0eOBChesf',
            ],
            [
                'value'   => 5300,  //実際付与されるポイント
                'price'   => 5000,  //支払い金額
                'service' => 300,//サービス差異
                'stripe_id'=>'price_1OXZ3YKoJdkajOL0Xuh40BWc',
            ],
            [
                'value'   => 10500,  //実際付与されるポイント
                'price'   => 10000,  //支払い金額
                'service' => 500,//サービス差異
                'stripe_id'=>'price_1OXZ4GKoJdkajOL0Rhb98kYK',
            ],
            [
                'value'   => 53000,  //実際付与されるポイント
                'price'   => 50000,  //支払い金額
                'service' => 3000,//サービス差異
                'stripe_id'=>'price_1OXZ55KoJdkajOL042ykDRjf',
            ],

        ];

    }
}
