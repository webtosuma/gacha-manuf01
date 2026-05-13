<?php

namespace App\Services\Manuf;

use App\Models\ManufPurchaseItem;
use App\Models\PointHistory;
use App\Models\User;
// use App\Models\UserPrize;
use App\Models\UserGachaHistory;
use App\Models\UserShipped;
/*
| =============================================
|  Manufacturer : ガチャタイトル 発送　サービス 
| =============================================
*/
class ShippedService
{
    /** 発送料金の計算 */
    public function calcShippedFee(int $item_count): int
    {
        /*.設定は。config.gachaに記述 */
        $basic_fee = config('manuf.shipped.fee',0);
        $item_count_unit =  config('manuf.shipped.item_count_unit',0);

        return $item_count_unit
        ? ceil( $item_count / $item_count_unit ) * $basic_fee
        : $basic_fee;
    }



    /**
     * 発送申請  StripeService::completedMethod にて利用
     */
    public function appliy(
        UserGachaHistory $gacha_history,
        User $user,
        int  $address_id,//発送先ID
    ): UserShipped
    {
        # ポイント履歴
        $point_history = PointHistory::create([
            'user_id'   => $user->id,
            'value'     => 0,//ポイント数
            'reason_id' => 22,//商品発送
        ]);

        # 発送履歴
        $shipped = UserShipped::create([
            'user_id' => $user->id,
            'user_address_id' => $address_id,
            'point_history_id' => $point_history->id,
            'state_id' => 11,
        ]);

        # 商品更新
        foreach ($gacha_history->user_prizes as $p) {
            $p->update([ 'shipped_id' => $shipped->id ]);
        }

        return $shipped;
    }
    
}