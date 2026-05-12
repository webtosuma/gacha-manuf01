<?php

namespace App\Services\manuf;

use Illuminate\Http\Request;
use App\Http\Controllers\UserAddressApiController;
use App\Models\ManufGachaTitleMachine;
use App\Models\ManufPurchaseHistory;
use App\Models\ManufPurchaseItem;
use App\Models\User;
use App\Models\UserAddress;
/*
| =============================================
|  Manufacturer : ガチャタイトル 購入　サービス 
| =============================================
*/
class PurchaseService
{
     # サービスの登録
     public function __construct(
        protected GachaTitleService $gachaTitleService, //
        protected ShippedService    $shippedService,    // 発送サービス
    ){}



    /* 購入履歴の登録 */
    public function createhistory(
        User $user,
        ManufGachaTitleMachine $machine,
        UserAddress $user_address,
        int $play_count,
        int $shipped_fee
    ): ManufPurchaseHistory
    {
        # 購入履歴(購入待ち)の新規登録
        $model   = new ManufPurchaseHistory;
        $history = ManufPurchaseHistory::create([
            'code'        => $model->CreateCode(),//履歴コード
            'user_id'     => $user->id,//
            'address_id'  => $user_address->id,
            'shipped_fee' => $shipped_fee,//発送料金
            'status'      => 'waiting',//状態:待機
        ]);

        # 購入アイテムの新規登録
        $item = ManufPurchaseItem::create([
            'user_id'     => $user->id,//
            'machine_id'  => $machine->id,   
            'history_id'  => $history->id,     
            'count'       => $play_count,
            'one_fee'     => $machine->price,          
        ]);

        return $history;
    }



    /**
     * 購入パラメーター情報取得
     *
     * @param Request $request
     * @return array
     */
    public function getPurchaseData(Request $request): array
    {
        # ユーザー
        $user = $request->user();

        # ガチャPLAY数
        $play_count = $request->play_count;

        # 選択されたガチャマシーン情報の取得
        $machine = $this->gachaTitleService->getMachine($request);
        $gacha_title = $machine->gacha_title;

        # 発送料金
        $shipped_fee = $this->shippedService->calcShippedFee($play_count);

        # お届け先アドレス
        $address_id = $request->user_address_id;

        $user_address = UserAddress::where('user_id', $user->id)
            ->where('id', $address_id)
            ->firstOrFail();

        # 選択のアドレスをデフォルトにする
        if ((bool) $request->default_address) {
            UserAddressApiController::UpdateDeffaultAddress($address_id);
        }

        # ガチャ料金(購入当時)
        $gacha_title_price = $gacha_title->price;

        # 小計料金
        $sub_total_fee = $gacha_title_price * $play_count;

        # 合計料金
        $total_fee = $sub_total_fee + $shipped_fee;

        return [
            'user'              => $user,
            'play_count'        => $play_count,
            'machine'           => $machine,
            'gacha_title'       => $gacha_title,
            'user_address'      => $user_address,
            'gacha_title_price' => $gacha_title_price,
            'shipped_fee'       => $shipped_fee,
            'sub_total_fee'     => $sub_total_fee,
            'total_fee'         => $total_fee,
        ];
    }


}