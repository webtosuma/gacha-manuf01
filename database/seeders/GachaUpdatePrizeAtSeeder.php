<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gacha;
use App\Models\GachaDiscription;
/*
| =============================================
|  ガチャ登録商品更新日時 更新 　シーダー
| =============================================
*/
class GachaUpdatePrizeAtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $gachas = Gacha::where('updated_prizes_at',null)->get();
       foreach ($gachas as $gacha)
       {
            $gacha->updated_prizes_at = $gacha->created_at;
            $gacha->save();
       }
    }
}
