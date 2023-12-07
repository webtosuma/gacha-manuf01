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
            ['rank'=>'ss', 'count'=>1, 'rank_id'=>'1', 'gacha_rank_id'=>'100', 'max_count'=>1,],
            ['rank'=>'s',  'count'=>2, 'rank_id'=>'2', 'gacha_rank_id'=>'200', 'max_count'=>2,],
            ['rank'=>'a',  'count'=>4, 'rank_id'=>'3', 'gacha_rank_id'=>'300', 'max_count'=>50,],

            ['rank'=>'b',  'count'=>5, 'rank_id'=>'4', 'gacha_rank_id'=>'400', 'max_count'=>50,],
            ['rank'=>'c',  'count'=>7, 'rank_id'=>'5', 'gacha_rank_id'=>'500', 'max_count'=>50,],
            ['rank'=>'d',  'count'=>8, 'rank_id'=>'6', 'gacha_rank_id'=>'600', 'max_count'=>149,],

            ['rank'=>'キリ番',    'count'=>1, 'rank_id'=>'2', 'gacha_rank_id'=>'310', 'max_count'=>1,],
            ['rank'=>'ゾロ目',    'count'=>1, 'rank_id'=>'2', 'gacha_rank_id'=>'320', 'max_count'=>1,],
            ['rank'=>'ラストワン', 'count'=>1, 'rank_id'=>'1', 'gacha_rank_id'=>'10', 'max_count'=>1,],


            // ['rank'=>'ss', 'count'=>1, 'rank_id'=>'1', 'gacha_rank_id'=>'100', 'max_count'=>1,],
            // ['rank'=>'s',  'count'=>2, 'rank_id'=>'2', 'gacha_rank_id'=>'200', 'max_count'=>2,],
            // ['rank'=>'a',  'count'=>4, 'rank_id'=>'3', 'gacha_rank_id'=>'300', 'max_count'=>50,],

            // ['rank'=>'b',  'count'=>5, 'rank_id'=>'4', 'gacha_rank_id'=>'400', 'max_count'=>50,],
            // ['rank'=>'c',  'count'=>7, 'rank_id'=>'5', 'gacha_rank_id'=>'500', 'max_count'=>50,],
            // ['rank'=>'d',  'count'=>8, 'rank_id'=>'6', 'gacha_rank_id'=>'600', 'max_count'=>1,],

            // ['rank'=>'キリ番',    'count'=>1, 'rank_id'=>'2', 'gacha_rank_id'=>'310', 'max_count'=>1,],
            // ['rank'=>'ゾロ目',    'count'=>1, 'rank_id'=>'2', 'gacha_rank_id'=>'320', 'max_count'=>1,],
            // ['rank'=>'ラストワン', 'count'=>1, 'rank_id'=>'1', 'gacha_rank_id'=>'10', 'max_count'=>1,],
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



    }

}
