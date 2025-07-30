<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserAddressApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreItem;
use App\Models\StoreKeep;
use App\Models\StoreHistory;
use App\Models\UserAddress;
/*
| =============================================
|  EC 購入手続き コントローラー
| =============================================
*/
class PurchaseController extends Controller
{
    /** 発送料金の計算 */
    public static function shippedPrice($request=null){ return 700; }


    /**
     * 手続き
     *
     * @param Request $request
     * @return \Illuminate\View\View
    */
    public function appli(Request $request)
    {
        # ログインユーザー
        $user = Auth::user();

        # 発送料金
        $shipped_price = PurchaseController::shippedPrice($request);

        # 直接注文した商品(ID・数量)
        $store_item_id    = $request->store_item_id;
        $store_item_count = $request->count;


        # いますぐ購入からの注文
        if( $request->store_item_id )
        {
            ##
            $store_item = StoreItem::find($request->store_item_id);

            ## エラーメッセージ
            if ( $message = $store_item->ErrCheckMessage($request) )
            {
                return redirect()->back()
                ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-triangle']);
            }

            # 今すぐ購入用のstore_keepを新規作成
            $store_keep = new StoreKeep([
                'user_id'       => $user->id,      //ユーザー　　　リレーション
                'store_item_id' => $store_item->id,//販売アイテム　リレーション
                'count'         => $request->count,//注文数
                'is_buy_now'    => 1,              //今すぐ購入
            ]);
            $store_keep->save();

            # 注文商品ID
            $store_keep_ids =  [ $store_keep->id ];

        }
        # 買い物カートからの注文
        else
        {
            $ids = $request->store_keep_ids;//カート商品ID($store_keep_ids)
            $store_keeps = StoreKeep::where('user_id',$user->id)->find($ids);//ログインユーザーのカート

            # 注文商品ID
            $store_keep_ids = !$store_keeps ? [] : $store_keeps->pluck('id')->toArray();

            # エラーメッセージ
            if ( $message = PurchaseController::ErrCheckMessage($request,$store_keeps) ){
                return redirect()->back()
                ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-triangle']);
            }
        }

        # 今すぐ購入のとき、商品ページURLの保存
        $r_buynow_item = isset($store_item) ? route('store.show',$store_item->code) : null;

        #
        return view('store.purchase.appli',compact(
            'shipped_price',  //発送料金
            'store_keep_ids', //買い物カートからの注文商品ID
            'r_buynow_item',  //すぐ購入のとき、商品ページURLの保存
        ));
    }



    /**
     * 完了
     *
     * @param String $store_history
     * @return \Illuminate\View\View
    */
    public function comp($store_history_code)
    {
        $user = Auth::user();
        $store_history =StoreHistory::where('code',$store_history_code)->first();

        return view('store.purchase.comp',compact('store_history'));
    }


    /*
     |===========
     | メソッド
     |===========
    */

        /* カート商品 (複数)のエラーメッセージ */
        public static function ErrCheckMessage($request,$store_keeps)
        {
            # ログアウト中のとき
            if(!$store_keeps){ return 'ご購入する商品を1つ以上選択してください。';}


            # 販売商品のエラーメッセージ
            $message =  null;
            foreach ($store_keeps as $store_keep)
            {
                $store_item = $store_keep->store_item;

                $message = $store_item->ErrCheckMessage($request);

                # エラーがあれば、メッセージを返す
                if($message){ return $message; }
            }


            return $message;
        }

}
