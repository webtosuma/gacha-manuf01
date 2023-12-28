<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gacha;
use App\Models\GachaDiscription;
/*
| =============================================
|  ガチャ詳細情報　シーダー
| =============================================
*/
class GachaDiscriptionSeeder extends Seeder
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
            self::CreateDiscriptions( $gacha );
        }
    }


    /**
     * 詳細情報作成
     *
     * @return Void
     */
    public function CreateDiscriptions( $gacha )
    {
        $datalist = self::DiscriptionDataList();
        $id = $gacha->id;

        foreach ($datalist as $data)
        {
            $data['gacha_id'] = $id;
            # ガチャ詳細の登録
            $gacha = new GachaDiscription( $data );
            $gacha->save();
        }
    }




    /**
     * 詳細情報データリスト
     *
     * @return Array
     */
    public function DiscriptionDataList()
    {

        return   [
            [
                'image'        => '', //画像
                'sorce'        => 'RankSSの説明文　', //説明文
                'gacha_rank_id'=> '100', //ランクID
            ],
            [
                'image'        => '', //画像
                'sorce'        => 'RankSの説明文　', //説明文
                'gacha_rank_id'=> '200', //ランクID
            ],
            [
                'image'        => '', //画像
                'sorce'        => 'RankAの説明文　', //説明文
                'gacha_rank_id'=> '300', //ランクID
            ],
            [
                // 'image'        => 'sample/gachas/g01/02prize.png', //画像
                'sorce'        => "RankBの説明文\n改行", //説明文
                'gacha_rank_id'=> '400', //ランクID
            ],
            [
                'image'        => '', //画像
                'sorce'        => "RankCの説明文\n改行", //説明文
                'gacha_rank_id'=> '500', //ランクID
            ],
            [
                'image'        => '', //画像
                'sorce'        => 'RankDの説明文　https://www.google.co.jp/', //説明文
                'gacha_rank_id'=> '600', //ランクID
            ],


            [
                'image'        => '', //画像
                'sorce'        => 'キリ番の説明文', //説明文
                'gacha_rank_id'=> '310', //ランクID
            ],
            [
                'image'        => '', //画像
                'sorce'        => 'ゾロ目の説明文', //説明文
                'gacha_rank_id'=> '320', //ランクID
            ],
            [
                'image'        => '', //画像
                'sorce'        => 'ラストワンの説明文', //説明文
                'gacha_rank_id'=> '10', //ランクID
            ],

        ];

    }
}
