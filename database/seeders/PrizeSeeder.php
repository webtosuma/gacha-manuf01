<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gacha;
use App\Models\Prize;
use App\Models\PrizeRank;

/*
| =============================================
|  ガチャの景品　シーダー　
| =============================================
*/
class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_array = [
            ['rank'=>'ss', 'count'=>1, 'rank_id'=>1, 'point'=>10000 ,],
            ['rank'=>'s',  'count'=>2, 'rank_id'=>2,  'point'=>5000 ,],
            ['rank'=>'a',  'count'=>4, 'rank_id'=>3,  'point'=>1000 ,],
            ['rank'=>'b',  'count'=>5, 'rank_id'=>4,  'point'=>500 ,],
            ['rank'=>'c',  'count'=>7, 'rank_id'=>5,  'point'=>100,],
            ['rank'=>'d',  'count'=>8, 'rank_id'=>6,  'point'=>50,],

        ];
        foreach ($data_array as $data) {
            $path = 'sample/prizes02/'.$data['rank'];

            for ($i=1; $i <= $data['count']; $i++) {
                $code = sprintf( $data['rank'].'-%02d', $i );

                $prize = new Prize([
                    'category_id'     => 1,//リレーション
                    'code'            => $code,//景品コード
                    'name'            => $code.'カード名',//名前
                    'image'           => sprintf( $path.'%02d.png', $i ),//画像
                    'rank_id'         => $data['rank_id'],//ランクID
                    'point'           => $data['point'],//交換ポイント値
                    'point_updated_at'=> now(),//交換ポイント値更新日時
                    'published_at'    => now(),//公開日時
                ]);
                $prize->save();
            }
        }


    }
}
