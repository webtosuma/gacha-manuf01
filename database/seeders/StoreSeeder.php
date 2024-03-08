<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/*
| =============================================
|  ストアー　シーダー
| =============================================
*/
class StoreSeeder extends Seeder
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
            $store = new \App\Models\Store($data);
            $store->save();
        }
    }



    /**
     * DATA
     *
     * @return Array
     */
    public function dataList()
    {
        return   [
            [
                'prize_id'    =>1,    //商品ID
                'category_id' =>1, //カテゴリーID
                'ticket_count'=>10,//交換チケット数
                'point_count' =>1000,
                'published_at'=>now(),//公開設定(利用しない->非公開*消さない)
                'count'       =>15,       //在庫数
                'user_id'     =>1,
            ],
            [
                'prize_id'    =>2,    //商品ID
                'category_id' =>1, //カテゴリーID
                'ticket_count'=>10,//交換チケット数
                'point_count' =>1000,
                'published_at'=>now(),//公開設定(利用しない->非公開*消さない)
                'count'       =>15,       //在庫数
                'user_id'     =>1,
            ],
            [
                'prize_id'    =>3,    //商品ID
                'category_id' =>1, //カテゴリーID
                'ticket_count'=>10,//交換チケット数
                'point_count' =>1000,
                'published_at'=>now(),//公開設定(利用しない->非公開*消さない)
                'count'       =>15,       //在庫数
                'user_id'     =>1,
            ],
            [
                'prize_id'    =>4,    //商品ID
                'category_id' =>1, //カテゴリーID
                'ticket_count'=>10,//交換チケット数
                'point_count' =>1000,
                'published_at'=>now(),//公開設定(利用しない->非公開*消さない)
                'count'       =>15,       //在庫数
                'user_id'     =>1,
            ],
            [
                'prize_id'    =>5,    //商品ID
                'category_id' =>1, //カテゴリーID
                'ticket_count'=>10,//交換チケット数
                'point_count' =>1000,
                'published_at'=>now(),//公開設定(利用しない->非公開*消さない)
                'count'       =>15,       //在庫数
                'user_id'     =>1,
            ],

        ];

    }
}
