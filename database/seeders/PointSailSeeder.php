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
                'value'   => 100,  //実際付与されるポイント
                'price'   => 110,  //管理者編集権限
                'service' => 0,//サービス差異
            ],
            [
                'value'   => 300,  //実際付与されるポイント
                'price'   => 330,  //管理者編集権限
                'service' => 0,//サービス差異
            ],
            [
                'value'   => 1000,  //実際付与されるポイント
                'price'   => 1100,  //管理者編集権限
                'service' => 0,//サービス差異
            ],
            [
                'value'   => 3100,  //実際付与されるポイント
                'price'   => 3300,  //管理者編集権限
                'service' => 100,//サービス差異
            ],
            [
                'value'   => 5200,  //実際付与されるポイント
                'price'   => 5500,  //管理者編集権限
                'service' => 200,//サービス差異
            ],
            [
                'value'   => 10500,  //実際付与されるポイント
                'price'   => 11000,  //管理者編集権限
                'service' => 500,//サービス差異
            ],
        ];

    }
}
