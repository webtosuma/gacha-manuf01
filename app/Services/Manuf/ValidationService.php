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
        $gacha = $machine->gacha;
        return $this->gachaPlayValidationService->index( $request, $gacha, $admin );

        # 筐体在庫数チェック
        
    }



}