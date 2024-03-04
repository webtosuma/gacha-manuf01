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
     * DATA
     *
     * @return Array
     */
    public function dataList()
    {
        return   [
            [
                'value'        => 1,     //チケット数
                'point'        => 1*1000,//交換するポイント数
                'is_published' => 1,//公開設定(利用しない->非公開*消さない)
            ],
            [
                'value'        => 3,     //チケット数
                'point'        => 3*1000,//交換するポイント数
                'is_published' => 1,//公開設定(利用しない->非公開*消さない)
            ],
            [
                'value'        => 5,     //チケット数
                'point'        => 5*1000,//交換するポイント数
                'is_published' => 1,//公開設定(利用しない->非公開*消さない)
            ],

        ];

    }
}
