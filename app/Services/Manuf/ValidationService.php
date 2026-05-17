<?php

namespace App\Services\Manuf;
use Illuminate\Http\Request;
use App\Models\Gacha;
use App\Models\GachaCategory;
use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitleMachine;
use App\Services\Gacha\PlayValidationService;
/*
| =============================================
|  Manufacturer : バリデーション サービス 
| =============================================
*/
class ValidationService{

    # サービスの登録
    public function __construct(
        protected PlayValidationService $gachaPlayValidationService, //ガチャPLAy
    ) {}


    /**
     * カテゴリー チェック
    */
    public function checkeCategory(GachaCategory $category):void 
    {
        if(
         ! $category->is_published //非公開のとき
        ){ abort(404); }
    }


    /**
     * ガチャタイトル チェック(公開のみ)
    */
    public function checkeGachaTitle(ManufGachaTitle $gacha_title):void 
    {
        # カテゴリーチェック
        $this->checkeCategory($gacha_title->category);

        if(
         ! $gacha_title->is_published //非公開・公開予約のとき
        ){ abort(404); }
    }


    /**
     * ガチャタイトル チェック(販売期間を含む)
    */
    public function checkeGachaTitlePurchase(ManufGachaTitle $gacha_title):void 
    {
        # ガチャタイトル チェック(公開)
        $this->checkeGachaTitle($gacha_title);

        if(
          ! $gacha_title->is_sales //販売中ではないとき
        ){ abort(404); }
    }



    /**
     * ガチャ筐体 チェック
    */
    public function checkeMachine(
        Request $request,
        ManufGachaTitleMachine $machine,
        Bool $admin=false
    ):? string
    {
        # ガチャタイトル チェック(販売期間を含む) 
        $this->checkeGachaTitlePurchase($machine->gacha_title);

        # ガチャPlay チェック
        $gacha   = $machine->gacha;
        $message = $this->gachaPlayValidationService->index( $request, $gacha, $admin );

        # マシーンチェック - 購入可能な数の確認
        if( $machine->not_purchase ){
            $message = 'このガチャマシーンを購入することはできません。';
        }
        
        return $message;
    }



    /**
     * 購入確認ページ・チェックアウト チェック
    */
    public function purchaseConfirm(
        Request $request,
        ManufGachaTitleMachine $machine,
        Bool $admin=false
    ):? string
    {
        # ガチャ筐体 チェック
        $message = $this->checkeMachine( $request, $machine, $admin );

        # ガチャのPLAY数の確認
        if( $machine->max_purchase_count < $request->play_count ){
            $message = '版画販売できるガチャの在庫数が足りません。';
        }

        # 販売回数の入力チェック 0以上
        if($request->play_count < 1){
            $message ='0以上の購入数を指定してください。'; 
        }

        # 販売回数の入力チェック 上限
        if( $request->play_count > config('manuf.purchase.max_playcount') ){
            $message = '版画販売できるガチャ数の範囲を超えています。';
        }

        # 発送住所の確認
        if( 
            ! $request->user()->addresses->where('id',$request->user_address_id)
            ->isNotEmpty()
         ){ abort(404); }



        return $message;
    }

}