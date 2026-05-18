<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ManufPurchaseHistory;
use App\Services\Manuf\PurchaseService;
use App\Services\Manuf\GachaTitleService;
use App\Services\Manuf\ShippedService;
use App\Services\Manuf\ValidationService;
use Illuminate\Support\Facades\Redirect;

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
        protected ValidationService $validationService, // バリデーションサービス
    ) {}




    /**
     * 購入[入力]
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function appliy( Request $request )
    {
        # 筐体コード入力チェック
        if( ! $request->gacha_key ){
            return redirect()->back()->with([
                'alert-danger'=>'ガチャマシンを選択してください。',
                'icon'=>'bi-exclamation-circle'
            ]);
        }

        # 選択されたガチャマシーン情報の取得
        $machine = $this->gachaTitleService->getMachine($request);
        $gacha_title = $machine->gacha_title;

        # 筐体 バリデーションチェック
        if( $message = $this->validationService->checkeMachine( 
            $request, $machine, $admin=false 
        ) ) {
            return redirect()->back()->with([
                'alert-danger'=>$message, 'icon'=>'bi-exclamation-circle'
            ]);
        }


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


        # 購入[確認] バリデーションチェック
        $machine = $params['machine'];
        if( $message = $this->validationService->purchaseConfirm(
            $request, $machine, $admin=false
        ) ) {
            return redirect()->back()->with([
                'alert-danger'=>$message, 'icon'=>'bi-exclamation-circle'
            ]);
        }
        
        
        return view('manuf.gacha.purchase.confirm', $params);
    }


    
    /**
     * 購入[キャンセル]
     * 
     * @param String $code
     * @return \Illuminate\Http\Response
     */
    public function cancel($code)
    {
        # ユーザー情報
        $user = Auth::user();

        # キャンセル処理
        $history = $this->purchaseService->gethistory($user,$code);
        $history->update([ 'status' => 'cancel' ]);

        # リダイレクト　->　[入力]
        return redirect()->route('manuf.gacha_title.purchase.appliy',[
            'gacha_key' => $history->items->first()->machine->key
        ]);
    }


    /**
     * 購入[完了]
     * 
     * @return \Illuminate\Http\Response
    */
    public function comp( String $code )
    {
        # 購入履歴の取得
        $user = Auth::user();
        $history = $this->purchaseService->gethistory($user,$code);

        return view('manuf.gacha.purchase.comp', [
            'history'       => $history,
            'gacha_title'   => $history->items->first()->machine->gacha_title,

            'shipped_fee'   => $history->shipped_fee,
            'sub_total_fee' => $history->sub_total_fee,
            'total_fee'     => $history->total_fee,

            'user_address'  => $history->user_address,
        ]);
    }



}
