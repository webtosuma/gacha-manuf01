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
                'order'  => 11,
            ],
            [
                'name'   => 'RankSS',
                'order'  => 12,
            ],
            [
                'name'   => 'RankA',
                'order'  => 111,
            ],
            [
                'name'   => 'RankB',
                'order'  => 112,
            ],

            [
                'name'   => 'RankC',
                'order'  => 113,
            ],
            [
                'name'   => 'RankD',
                'order'  => 114,
            ],


        ];

    }

}
