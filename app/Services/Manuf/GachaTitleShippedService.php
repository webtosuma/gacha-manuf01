<?php

namespace App\Services\manuf;

use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitleMachine;
/*
| =============================================
|  Manufacturer : ガチャタイトル 発送　サービス 
| =============================================
*/
class GachaTitleShippedService
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

}