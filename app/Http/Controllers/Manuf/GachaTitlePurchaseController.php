<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserAddressApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Manuf\GachaTitleService;
use App\Services\Manuf\GachaTitleShippedService;
use App\Models\UserAddress;

/*
| =============================================
|  Manufacturer:ガチャタイトル　購入 コントローラー
| =============================================
*/
class GachaTitlePurchaseController extends Controller
{
    # サービスの登録
    public function __construct(
        protected GachaTitleService        $gachaTitleService,        //
        protected GachaTitleShippedService $gachaTitleShippedService, // 発送サービス
    ) {}



    /**
     * 購入[入力]
     * 
     * @param Request $request 
     * @param String $category_code //カテゴリーコード名
     * @param String $title_code    //ガチャタイトル・キー
     * @return \Illuminate\Http\Response
     */
    public function appliy( 
        Request $request, $category_code, $title_code
    ){
        # ガチャタイトル詳細情報の取得/カテゴリーのコードチェック
        $gacha_title = $this->gachaTitleService->getGachaTitle($title_code, $category_code);


        # 選択されたガチャマシーン情報の取得/ガチャタイトル・公開チェック
        list( $machine, $alert_array ) 
        = $this->gachaTitleService->getMachine($request, $gacha_title);

        # エラーメッセージ
        if( $alert_array ){
            return redirect()->back()->with($alert_array);
        }

        # 発送料金
        $shipped_fee = $this->gachaTitleShippedService->calcShippedFee($play_count=1);

        // dd([ $machine, $alert_array]);
        // dd($request->all());

        return view('manuf.gacha.purchase.appliy', compact(
            'gacha_title',
            'machine',
            'category_code',
            'shipped_fee',
        ));
    }



    /**
     * 購入[確認]
     * 
     * @param Request $request 
     * @param String $category_code //カテゴリーコード名
     * @param String $title_code    //ガチャタイトル・キー
     * @return \Illuminate\Http\Response
     */
    public function confirm( 
        Request $request, $category_code, $title_code
    ){
        # ガチャタイトル詳細情報の取得/カテゴリーのコードチェック
        $gacha_title = $this->gachaTitleService->getGachaTitle($title_code, $category_code);


        # 選択されたガチャマシーン情報の取得/ガチャタイトル・公開チェック
        list( $machine, $alert_array ) 
        = $this->gachaTitleService->getMachine($request, $gacha_title);

        # エラーメッセージ
        if( $alert_array ){
            return redirect()->back()->with($alert_array);
        }


        # 選択のアドレスをデフォルトにする
        $address_id      = $request->user_address_id;
        $default_address = (bool) $request->default_address;//選択のアドレスをデフォルトにする
        if( $default_address ){
            UserAddressApiController::UpdateDeffaultAddress( $address_id );
        }
        
        # お届け先アドレス
        $user = Auth::user();
        $user_address = UserAddress::where('user_id',$user->id)->find($address_id);
        if( !$user_address ){ return abort(404); }//データがないとき


        # ガチャPLAY数
        $play_count = $request->play_count;
        /* ~PLAy数のチェック~ */

        # ガチャ料金(購入当時)
        $gacha_title_price = $gacha_title->price;

        # 発送料金
        $shipped_fee = $this->gachaTitleShippedService->calcShippedFee($play_count);

        # 小計料金
        $sub_total_fee = $gacha_title->price * $play_count;

        # 合計料金
        $total_fee = $sub_total_fee + $shipped_fee ;


        return view('manuf.gacha.purchase.confirm', compact(
            'gacha_title',
            'machine',
            'category_code',
            'user_address',
            'play_count',

            'gacha_title_price',//ガチャ料金(購入当時)
            'shipped_fee',  //発送料金
            'sub_total_fee',//小計料金
            'total_fee',    //合計料金
        ));
    }



    /**
     * ガチャタイトルの筐体 購入[決済チェックアウト]
     * 
     * @param Request $request 
     * @param String $category_code //カテゴリーコード名
     * @param String $title_code    //ガチャタイトル・キー
     * @return \Illuminate\Http\Response
    */
    public function checkout(Request $request)
    {
        return 'check out!';
    }


}
