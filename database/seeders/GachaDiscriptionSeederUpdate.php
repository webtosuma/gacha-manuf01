<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gacha;
use App\Models\GachaDiscription;
/*
| =============================================
|  ガチャ詳細情報の追加　シーダー
| =============================================
*/
class GachaDiscriptionSeederUpdate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gachas = Gacha::all();
        foreach ($gachas as $gacha) {
            self::CreateDiscriptions( $gacha );
        }
    }


    /**
     * 詳細情報作成
     *
     * @return Void
     */
    public function CreateDiscriptions( $gacha )
    {
        $rank_array = GachaDiscription::gacha_ranks();
        foreach ($rank_array as $rank_id => $rank)
        {
            # 登録情報があればスキップ
            $old_gacha_discription = GachaDiscription::
            where('gacha_id', $gacha->id)
            ->where('gacha_rank_id', $rank_id)
            ->first();
            if($old_gacha_discription){ continue; }

            # ガチャ詳細の登録
            $gacha_discription = new GachaDiscription([
                'gacha_id'      => $gacha->id, //ガチャリレーション
                'gacha_rank_id' => $rank_id,   //ランクID
            ]);
            $gacha_discription->save();
        }
    }

}
