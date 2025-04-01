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
        // $this->call(AdminSeeder::class);
        // $this->call(UserSeeder::class);//テスト用

        /* 本番利用 */
        // $this->call(PointSailSeeder::class);//販売用ポイント
        // $this->call(GachaCategorySeeder::class);//ガチャのカテゴリーグループ
        // $this->call(MovieSeeder::class);//演出動画
        // $this->call(PrizeRankSeeder::class);//商品のランク
        $this->call(PointSailSubscriptionSeeder::class);//販売用サブスクプラン


        /* テスト用 */
        // $this->call(GachaSeeder::class);//ガチャ
        // $this->call(GachaDiscriptionSeeder::class);//ガチャ詳細情報
        // $this->call(PrizeSeeder::class);//景品
        // $this->call(GachaPrizeSeeder::class);//各ガチャの景品
        // $this->call(GachaRankMovieSeeder::class);//各ガチャの演出動画設定
        // $this->call(InfomationSeeder::class);//お知らせ
        // $this->call(TicketSailSeeder::class);//販売用チケット
        // $this->call(StoreSeeder::class);//販売商品

        /* 追加要素 */
        $this->call(GachaDiscriptionSeederUpdate::class);//ガチャ詳細情報の追加
        $this->call(GachaUpdatePrizeAtSeeder::class);    //ガチャ登録商品更新日時

    }
}
