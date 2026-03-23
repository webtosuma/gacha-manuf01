<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* アカウント */

        /* 本番利用 */
        $this->call(AdminSeeder::class);
        $this->call(PointSailSeeder::class);//販売用ポイント
        $this->call(PrizeRankSeeder::class);//商品のランク


        /* テスト用 */
        $this->call(UserSeeder::class);//テスト用ユーザー
        $this->call(GachaCategorySeeder::class);//ガチャのカテゴリーグループ
        $this->call(MovieSeeder::class);//演出動画
        // $this->call(PointSailSubscriptionSeeder::class);//販売用サブスクプラン
        // $this->call(GachaSeeder::class);//ガチャ
        // $this->call(GachaDiscriptionSeeder::class);//ガチャ詳細情報
        // $this->call(PrizeSeeder::class);//景品
        // $this->call(GachaPrizeSeeder::class);//各ガチャの景品
        // $this->call(GachaRankMovieSeeder::class);//各ガチャの演出動画設定
        $this->call(InfomationSeeder::class);//お知らせ
        // $this->call(TicketSailSeeder::class);//販売用チケット
        // $this->call(StoreSeeder::class);//販売商品
        // $this->call(CouponSeeder::class);//クーポン
        // $this->call(StoreItemSeeder::class);//EC販売アイテム
        $this->call(ManufGachaTitleSeeder::class);//Manufガチャタイトル



        /* 追加要素 */
        $this->call(GachaDiscriptionSeederUpdate::class);//ガチャ詳細情報の追加
        $this->call(GachaUpdatePrizeAtSeeder::class);    //ガチャ登録商品更新日時

    }
}
