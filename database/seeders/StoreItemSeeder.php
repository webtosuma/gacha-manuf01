<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreItem;
use App\Models\GachaCategory;
/*
| =============================================
|  EC 販売アイテム　シーダー　
| =============================================
*/
class StoreItemSeeder extends Seeder
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
            $data['code'] = StoreItem::CreateCode();
            $create = new StoreItem($data);
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
                'category_id'       => GachaCategory::first()->id,//カテゴリー　リレーション
                'item_name'         => '商品A',//アイテム名
                'discription'       => '説明文texttexttext',//説明文

                'price'             => 1980,//販売価格
                'count'             => 10,//在庫数
                'points_redemption' => 3,//還元ポイント

                'is_slide'          => true,//スライド表示
                'published_at'      => now(),//公開日時
            ],
            [
                'category_id'       => GachaCategory::first()->id,//カテゴリー　リレーション
                'item_name'         => '商品B',//アイテム名
                'discription'       => '説明文texttexttext',//説明文

                'price'             => 1980,//販売価格
                'count'             => 10,//在庫数
                'points_redemption' => 3,//還元ポイント

                'is_slide'          => false,//スライド表示
                'published_at'      => now(),//公開日時
            ],
            [
                'category_id'       => GachaCategory::first()->id,//カテゴリー　リレーション
                'item_name'         => '商品C',//アイテム名
                'discription'       => '説明文texttexttext',//説明文

                'price'             => 1980,//販売価格
                'count'             => 10,//在庫数
                'points_redemption' => 3,//還元ポイント

                'is_slide'          => false,//スライド表示
                'published_at'      => now(),//公開日時
            ],
            [
                'category_id'       => GachaCategory::get()[1]->id,//カテゴリー　リレーション
                'item_name'         => '商品D',//アイテム名
                'discription'       => '説明文texttexttext',//説明文

                'price'             => 1980,//販売価格
                'count'             => 10,//在庫数
                'points_redemption' => 3,//還元ポイント

                'is_slide'          => false,//スライド表示
                'published_at'      => now(),//公開日時
            ],
            [
                'category_id'       => GachaCategory::get()[1]->id,//カテゴリー　リレーション
                'item_name'         => '商品E',//アイテム名
                'discription'       => '説明文texttexttext',//説明文

                'price'             => 1980,//販売価格
                'count'             => 10,//在庫数
                'points_redemption' => 3,//還元ポイント

                'is_slide'          => false,//スライド表示
                'published_at'      => now(),//公開日時
            ],
            [
                'category_id'       => GachaCategory::get()[1]->id,//カテゴリー　リレーション
                'item_name'         => '商品D',//アイテム名
                'discription'       => '説明文texttexttext',//説明文

                'price'             => 1980,//販売価格
                'count'             => 10,//在庫数
                'points_redemption' => 3,//還元ポイント

                'is_slide'          => false,//スライド表示
                'published_at'      => now(),//公開日時
            ],
        ];

    }

}
