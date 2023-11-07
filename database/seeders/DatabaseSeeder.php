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


        $this->call(PointSailSeeder::class);//販売用ポイント


    }
}
