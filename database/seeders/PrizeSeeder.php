<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gacha;
use App\Models\Prize;

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
        # XAランク景品の登録
        $path = 'sample/prizes/s';
        for ($i=1; $i <= 3; $i++) {
            $prize = new Prize([
                'category_id'     => 1,//リレーション
                'code'            => sprintf('xa-%02d',$i),//景品コード
                'name'            => sprintf('xa-%02dカード名',$i),//名前
                'image'           => sprintf($path.'%02d.png',$i),//画像
                'rank_id'         => 'XA',//ランクID
                'point'           => 1000,//交換ポイント値
                'point_updated_at'=> now(),//交換ポイント値更新日時
                'published_at'    => now(),//公開日時
            ]);
            $prize->save();
        }

        # XBランク景品の登録
        $path = 'sample/prizes/a';
        for ($i=1; $i <= 8; $i++) {
            $prize = new Prize([
                'category_id'     => 1,//リレーション
                'code'            => sprintf('xa-%02d',$i),//景品コード
                'name'            => sprintf('xa-%02dカード名',$i),//名前
                'image'           => sprintf($path.'%02d.png',$i),//画像
                'rank_id'         => 'XB',//ランクID
                'point'           => 500,//交換ポイント値
                'point_updated_at'=> now(),//交換ポイント値更新日時
                'published_at'    => now(),//公開日時
            ]);
            $prize->save();
        }

        # XCランク景品の登録
        $path = 'sample/prizes/a';
        for ($i=1; $i <= 8; $i++) {
            $prize = new Prize([
                'category_id'     => 1,//リレーション
                'code'            => sprintf('xa-%02d',$i),//景品コード
                'name'            => sprintf('xa-%02dカード名',$i),//名前
                'image'           => sprintf($path.'%02d.png',$i),//画像
                'rank_id'         => 'XC',//ランクID
                'point'           => 100,//交換ポイント値
                'point_updated_at'=> now(),//交換ポイント値更新日時
                'published_at'    => now(),//公開日時
            ]);
            $prize->save();
        }

        # XDランク景品の登録
        $path = 'sample/prizes/a';
        for ($i=1; $i <= 8; $i++) {
            $prize = new Prize([
                'category_id'     => 1,//リレーション
                'code'            => sprintf('xa-%02d',$i),//景品コード
                'name'            => sprintf('xa-%02dカード名',$i),//名前
                'image'           => sprintf($path.'%02d.png',$i),//画像
                'rank_id'         => 'XD',//ランクID
                'point'           => 50,//交換ポイント値
                'point_updated_at'=> now(),//交換ポイント値更新日時
                'published_at'    => now(),//公開日時
            ]);
            $prize->save();
        }
    }
}
