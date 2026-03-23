<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManufGachaTitle;
/*
| =============================================
|  Manug　シーダー
| =============================================
*/
class ManufGachaTitleSeeder extends Seeder
{
    public function run(): void
    {

        ManufGachaTitle::factory()->count(10)->create();
    }
}
