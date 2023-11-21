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
        $this->call(UserSeeder::class);//テスト用
        $this->call(AdminSeeder::class);

        /* 本番利用 */
        $this->call(PointSailSeeder::class);//販売用ポイント
        $this->call(GachaCategorySeeder::class);//ガチャのカテゴリーグループ
        $this->call(PrizeRankSeeder::class);//商品のランク


        /* テスト用 */
        $this->call(GachaSeeder::class);//ガチャ
        $this->call(GachaDiscriptionSeeder::class);//ガチャ詳細情報
        $this->call(PrizeSeeder::class);//景品
        $this->call(GachaPrizeSeeder::class);//各ガチャの景品



    }
}
