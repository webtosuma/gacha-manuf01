<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Infomation;

/*
| =============================================
|  お知らせ　シーダー
| =============================================
*/
class InfomationSeeder extends Seeder
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
            # ガチャの登録
            $infomation = new Infomation($data);
            $infomation->save();

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
                'title'        => 'テストお知らせ',//題名
                'body'         => "テスト\nテスト\nテスト",//本文
                'image'        => 'sample/gachas/g01/top.png',//画像
                'published_at' => now(),//公開日時
            ],
            [
                'title'        => 'テスト：ユーザー向けお知らせ',//題名
                'body'         => "テスト\nテスト\nテスト",//本文
                'image'        => 'sample/gachas/g01/top.png',//画像
                'published_at' => now(),//公開日時
                'user_id' => 1,
            ],
            [
                'title'        => 'テスト：ユーザー向けお知らせ',//題名
                'body'         => "テスト\nテスト\nテスト",//本文
                'image'        => 'sample/gachas/g01/top.png',//画像
                'published_at' => now(),//公開日時
                'user_id' => 4,
            ],
            [
                'title'        => 'テスト：非公開',//題名
                'body'         => "テスト\nテスト\nテスト",//本文
                'image'        => 'sample/gachas/g01/top.png',//画像
            ],

        ];

    }
}
