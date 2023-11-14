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
                // 'gacha_id' => 1,
                'image'    => 'sample/gachas/g01/01prize.png', //画像
                'sorce'    => '1等賞の説明文　', //説明文
                'rank_id'  => 'XA', //ランクID
            ],
            [
                'image'    => 'sample/gachas/g01/02prize.png', //画像
                'sorce'    => "2等賞の説明文\n改行", //説明文
                'rank_id'  => 'XB', //ランクID
            ],
            [
                'image'    => 'sample/gachas/g01/03prize.png', //画像
                'sorce'    => "3等賞の説明文\n改行", //説明文
                'rank_id'  => 'XC', //ランクID
            ],
            [
                'image'    => 'sample/gachas/g01/04prize.png', //画像
                'sorce'    => '4等賞の説明文　https://www.google.co.jp/', //説明文
                'rank_id'  => 'XD', //ランクID
            ],

        ];

    }
}
