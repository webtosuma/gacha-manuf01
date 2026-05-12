<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ManufPurchaseHistory;
use App\Services\Manuf\PurchaseService;
use App\Services\Manuf\GachaTitleService;
use App\Services\Manuf\ShippedService;
/*
| =============================================
|  Manufacturer:ガチャタイトル　購入 コントローラー
| =============================================
*/
class PurchaseController extends Controller
{
    # サービスの登録
    public function __construct(
        protected PurchaseService   $purchaseService,
        protected GachaTitleService $gachaTitleService, //
        protected ShippedService    $shippedService,    // 発送サービス
    ) {}




    /**
     * 購入[入力]
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function appliy( Request $request )
    {
        # 選択されたガチャマシーン情報の取得
        $machine = $this->gachaTitleService->getMachine($request);
        $gacha_title = $machine->gacha_title;

        # 発送料金
        $shipped_fee = $this->shippedService->calcShippedFee($play_count=1);


        return view('manuf.gacha.purchase.appliy', compact(
            'machine',
            'gacha_title',
            'shipped_fee',
        ));
    }


    
    /**
     * 購入[確認]
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function confirm( Request $request )
    {
        # 購入パラメーター
        $params = $this->purchaseService->getPurchaseData($request);



        return view('manuf.gacha.purchase.confirm', $params);
    }




    /**
     * 購入[完了]
     * 
     * @return \Illuminate\Http\Response
    */
    public function comp(
        String $code
    ){
        $user = Auth::user();

        # 購入履歴の取得
        $history = $this->purchaseService->gethistory($user,$code);

        # ガチャタイトル詳細情報の取得/カテゴリーのコードチェック
        $gacha_title = $this->gachaTitleService->getGachaTitle($title_code, $category_code);


        # 選択されたガチャマシーン情報の取得
        list( $machine, $alert_array ) 
        = $this->gachaTitleService->getMachine($request, $gacha_title);
        if( $alert_array ){ return redirect()->back()->with($alert_array); }//エラーメッセージ


        # 購入履歴
        $history = ManufPurchaseHistory::where('code',$code)
        ->firstOrFail();//データなしの場合、404


        return view('manuf.gacha.purchase.confirm', compact( 'history' ));
    }



}
