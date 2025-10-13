<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
/*
| =============================================
|  ガチャ　シーダー　
| =============================================
*/
class GachaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = GachaCategory::all();
        $datalist = self::dataList();


        foreach ($categories as $category) {
            # code...
            foreach ($datalist as $data)
            {
                $data['category_id'] = $category->id;
                $data['key']  = Str::random(16);
                $data['type'] = config('gacha.defaults_type','');

                # ガチャの登録
                $gacha = new Gacha($data);
                $gacha->save();

            }
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
                'name'  => 'ガチャ01',//名前
                'image' => 'sample/gachas/g01/top.png',//イメージ画像
                'one_play_point' => 100,//1回PLAYポイント数
                'ten_play_point' => 1000,//10回PLAYポイント数
                'published_at'   => now(),//公開設定(利用しない->非公開*消さない)
            ],
            [
                'name'  => 'ガチャ02',//名前
                'image' => 'sample/gachas/g02/top.png',//イメージ画像
                'one_play_point' => 500,//1回PLAYポイント数
                'ten_play_point' => 5000,//10回PLAYポイント数
                'published_at'   => now(),//公開設定(利用しない->非公開*消さない)
            ],
            [
                'name'  => 'ガチャ03',//名前
                'image' => 'sample/gachas/g01/top.png',//イメージ画像
                'one_play_point' => 100,//1回PLAYポイント数
                'ten_play_point' => 1000,//10回PLAYポイント数
                'published_at'   => null,//公開設定(利用しない->非公開*消さない)
            ],
            [
                'name'  => 'ガチャ04',//名前
                'image' => 'sample/gachas/g01/top.png',//イメージ画像
                'one_play_point' => 100,//1回PLAYポイント数
                'ten_play_point' => 1000,//10回PLAYポイント数
                'published_at'   => '2025-01-01 00:00:00',//公開設定(利用しない->非公開*消さない)
            ],

        ];

    }
}
