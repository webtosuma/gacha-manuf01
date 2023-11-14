<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\Prize;
/*
| =============================================
|  ガチャの景品　シーダー
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
        # XAランク景品の登録

            $rank_id = 'XA';

            $prizes = Prize::inRandomOrder()
            ->where('rank_id',$rank_id)
            ->limit(2)->get();

            foreach ($prizes as $prize) {
                $gacha_prize = new GachaPrize([
                    'gacha_id'        => $gacha->id, //ガチャの種類リレーション
                    'prize_id'        => 1, //景品リレーション
                    'rank_id'         => $rank_id, //ランクID
                    'max_count'       => 1, //景品総数
                    'remaining_count' => 1, //景品残数
                ]);
                $gacha_prize->save();
            }

        # XBランク景品の登録

            $rank_id = 'XB';

            $prizes = Prize::inRandomOrder()
            ->where('rank_id',$rank_id)
            ->limit(3)->get();

            foreach ($prizes as $prize) {
                $gacha_prize = new GachaPrize([
                    'gacha_id'        => $gacha->id, //ガチャの種類リレーション
                    'prize_id'        => $prize->id, //景品リレーション
                    'rank_id'         => $rank_id, //ランクID
                    'max_count'       => 1, //景品総数
                    'remaining_count' => 1, //景品残数
                ]);
                $gacha_prize->save();
            }

        # XCランク景品の登録

            $rank_id = 'XC';

            $prizes = Prize::inRandomOrder()
            ->where('rank_id',$rank_id)
            ->limit(5)->get();

            foreach ($prizes as $prize) {
                $gacha_prize = new GachaPrize([
                    'gacha_id'        => $gacha->id, //ガチャの種類リレーション
                    'prize_id'        => $prize->id, //景品リレーション
                    'rank_id'         => $rank_id, //ランクID
                    'max_count'       => 1, //景品総数
                    'remaining_count' => 1, //景品残数
                ]);
                $gacha_prize->save();
            }

        # XDランク景品の登録

            $rank_id = 'XD';

            $prizes = Prize::inRandomOrder()
            ->where('rank_id',$rank_id)
            ->limit(5)->get();

            foreach ($prizes as $prize) {
                $gacha_prize = new GachaPrize([
                    'gacha_id'        => $gacha->id, //ガチャの種類リレーション
                    'prize_id'        => $prize->id, //景品リレーション
                    'rank_id'         => $rank_id, //ランクID
                    'max_count'       => 18, //景品総数
                    'remaining_count' => 18, //景品残数
                ]);
                $gacha_prize->save();
            }

        //

    }
}
