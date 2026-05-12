<?php

namespace App\Services\manuf;
use Illuminate\Http\Request;
use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitleMachine;
/*
| =============================================
|  Manufacturer : ガチャタイトル サービス 
| =============================================
*/
class GachaTitleService
{
    /**
     * ガチャタイトル詳細情報の取得/カテゴリーのコードチェック
     */
    public function getGachaTitle(
        String $title_code,    //ガチャタイトルのコード
        String $category_code  //カテゴリーのコード
    ): ManufGachaTitle
    {
        # ガチャタイトル
        $gacha_title = ManufGachaTitle::where('code',$title_code)->first();

        # キーのチェック
        if(
            !isset($gacha_title)
            || $gacha_title->category->code_name!=$category_code
            || !$gacha_title->is_published//公開有無
        ){ abort(404); }

        return $gacha_title;
    }




    /**
     * ガチャタイトル詳細情報の取得/カテゴリーのコードチェック
     */
    public function getMachine(
        Request $request,    //
        // ManufGachaTitle $gacha_title //ガチャタイトル
    ): ManufGachaTitleMachine
    {
        # ガチャキー
        $gacha_key = $request->gacha_key; 

        # ガチャキーに該当するガチャ
        return ManufGachaTitleMachine::whereHas('gacha', function ($query) use ($gacha_key) {
            $query->where('key', $gacha_key)
                ->whereNotNull('published_at');
        })
        ->firstOrFail();//データなしの場合、404
    }




}