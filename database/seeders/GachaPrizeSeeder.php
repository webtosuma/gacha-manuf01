<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\Prize;
/*
| =============================================
|  ガチャの商品　シーダー
| =============================================
*/
class GachaPrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gachas = Gacha::all();
        foreach ($gachas as $gacha) {
            self::create($gacha);
        }
    }


    /**
     * １つのガチャのカード登録
     *
     * @return void
     */
    public function create( $gacha )
    {
        $data_array = [
            ['rank'=>'ラストワン', 'count'=>1, 'rank_id'=>'111', 'gacha_rank_id'=>'11', 'max_count'=>1,],
            ['rank'=>'ゾロ目',    'count'=>1, 'rank_id'=>'112', 'gacha_rank_id'=>'21', 'max_count'=>1,],
            ['rank'=>'ss', 'count'=>1, 'rank_id'=>'101', 'gacha_rank_id'=>'101', 'max_count'=>1,],
            ['rank'=>'s',  'count'=>2, 'rank_id'=>'102', 'gacha_rank_id'=>'102', 'max_count'=>1,],
            ['rank'=>'a',  'count'=>4, 'rank_id'=>'111', 'gacha_rank_id'=>'111', 'max_count'=>1,],
            ['rank'=>'b',  'count'=>5, 'rank_id'=>'112', 'gacha_rank_id'=>'112', 'max_count'=>1,],
            ['rank'=>'c',  'count'=>6, 'rank_id'=>'113', 'gacha_rank_id'=>'113', 'max_count'=>1,],
            ['rank'=>'d',  'count'=>8, 'rank_id'=>'114', 'gacha_rank_id'=>'114', 'max_count'=>10,],

        ];
        foreach ($data_array as $data) {

            $gacha_rank_id = $data['gacha_rank_id'];
            $rank_id = $data['rank_id'];
            $count = $data['count'];

            $prizes = Prize::inRandomOrder()
            ->where('rank_id', $rank_id)
            ->limit($count)->get();


            foreach ($prizes as $prize) {
                $gacha_prize = new GachaPrize([
                    'gacha_id'        => $gacha->id, //ガチャの種類リレーション
                    'prize_id'        => $prize->id, //景品リレーション
                    'gacha_rank_id'   => $gacha_rank_id, //ランクID
                    'max_count'       => $data['max_count'], //景品総数
                    'remaining_count' => $data['max_count'], //景品残数
                ]);
                $gacha_prize->save();
            }
        }



        // # 101ランク景品の登録

        //     $gacha_rank_id = '101';
        //     $prize_lank_id = 1;

        //     $prizes = Prize::inRandomOrder()
        //     ->where('rank_id', $prize_lank_id)
        //     ->limit(2)->get();

        //     foreach ($prizes as $prize) {
        //         $gacha_prize = new GachaPrize([
        //             'gacha_id'        => $gacha->id, //ガチャの種類リレーション
        //             'prize_id'        => 1, //景品リレーション
        //             'gacha_rank_id'   => $gacha_rank_id, //ランクID
        //             'max_count'       => 1, //景品総数
        //             'remaining_count' => 1, //景品残数
        //         ]);
        //         $gacha_prize->save();
        //     }

        // # 102ランク景品の登録

        //     $gacha_rank_id = '102';
        //     $prize_lank_id = 2;

        //     $prizes = Prize::inRandomOrder()
        //     ->where('rank_id', $prize_lank_id)
        //     ->limit(3)->get();

        //     foreach ($prizes as $prize) {
        //         $gacha_prize = new GachaPrize([
        //             'gacha_id'        => $gacha->id, //ガチャの種類リレーション
        //             'prize_id'        => $prize->id, //景品リレーション
        //             'gacha_rank_id'   => $gacha_rank_id, //ランクID
        //             'max_count'       => 1, //景品総数
        //             'remaining_count' => 1, //景品残数
        //         ]);
        //         $gacha_prize->save();
        //     }

        // # 103ランク景品の登録

        //     $gacha_rank_id = '103';
        //     $prize_lank_id = 3;

        //     $prizes = Prize::inRandomOrder()
        //     ->where('rank_id', $prize_lank_id)
        //     ->limit(5)->get();

        //     foreach ($prizes as $prize) {
        //         $gacha_prize = new GachaPrize([
        //             'gacha_id'        => $gacha->id, //ガチャの種類リレーション
        //             'prize_id'        => $prize->id, //景品リレーション
        //             'gacha_rank_id'   => $gacha_rank_id, //ランクID
        //             'max_count'       => 1, //景品総数
        //             'remaining_count' => 1, //景品残数
        //         ]);
        //         $gacha_prize->save();
        //     }

        // # 104ランク景品の登録

        //     $gacha_rank_id = '104';
        //     $prize_lank_id = 4;

        //     $prizes = Prize::inRandomOrder()
        //     ->where('rank_id', $prize_lank_id)
        //     ->limit(5)->get();

        //     foreach ($prizes as $prize) {
        //         $gacha_prize = new GachaPrize([
        //             'gacha_id'        => $gacha->id, //ガチャの種類リレーション
        //             'prize_id'        => $prize->id, //景品リレーション
        //             'gacha_rank_id'   => $gacha_rank_id, //ランクID
        //             'max_count'       => 18, //景品総数
        //             'remaining_count' => 18, //景品残数
        //         ]);
        //         $gacha_prize->save();
        //     }

        //

    }

}
