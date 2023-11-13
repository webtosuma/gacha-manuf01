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

        /* テスト用 */
        $this->call(GachaCategorySeeder::class);//ガチャのカテゴリーグループ
        $this->call(GachaSeeder::class);//ガチャ

    }
}
