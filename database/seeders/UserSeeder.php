<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user
        = \App\Models\User::factory(3)->create();
        // $user_address
        // = \App\Models\UserAddress::factory()->create();
    }
}
