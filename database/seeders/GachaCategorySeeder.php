<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GachaCategory;
/*
| =============================================
|  ガチャのカテゴリーグループ　シーダー
| =============================================
*/
class GachaCategorySeeder extends Seeder
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
            # データの保存
            $create = new GachaCategory($data);
            $create->save();
        }
    }


    /**
     * データリスト
     *
     * @return Array
     */
    public function dataList()
    {

        return   [
            [
                'name'         => 'ワンピース',//名前
                'code_name'    => 'onepiece',//'コードネーム（ルーティング用）'
                'bg_image'     => 'site/image/bg03.png',//'背景画像'
            ],
            [
                'name'         => 'ポケモン',//名前
                'code_name'    => 'pokemon',//'コードネーム（ルーティング用）'
                'bg_image'     => 'site/image/bg01.jpg',//'背景画像'
            ],
            [
                'name'         => '遊戯王',//名前
                'code_name'    => 'yugio',//'コードネーム（ルーティング用）'
                'bg_image'     => 'site/image/bg04.jpg',//'背景画像'
                'is_published' => 0,//公開日時
            ],
            [
                'name'         => 'ドラゴンボール',//名前
                'code_name'    => 'dragonball',//'コードネーム（ルーティング用）'
                'bg_image'     => 'site/image/bg02.jpg',//'背景画像'
            ],
        ];

    }
}
