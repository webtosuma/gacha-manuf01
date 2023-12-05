<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrizeRank;
/*
| =============================================
|  ガチャの商品 ランク　シーダー　
| =============================================
*/
class PrizeRankSeeder extends Seeder
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
            $prize_rank = new PrizeRank($data);
            $prize_rank->save();
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
                'name'   => 'RankSS',
                'order'  => 100,
            ],
            [
                'name'   => 'RankS',
                'order'  => 200,
            ],

            [
                'name'   => 'RankA',
                'order'  => 300,
            ],
            [
                'name'   => 'RankB',
                'order'  => 400,
            ],

            [
                'name'   => 'RankC',
                'order'  => 500,
            ],
            [
                'name'   => 'RankD',
                'order'  => 600,
            ],
        ];

    }

}
