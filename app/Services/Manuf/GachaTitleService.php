<?php

namespace App\Services\manuf;

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
        $title_code,    //ガチャタイトルのコード
        $category_code  //カテゴリーのコード
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
        $request,    //
        $gacha_title //ガチャタイトル
    ): Array
    {
        $gacha_key = $request->gacha_key; 

        $machine = ManufGachaTitleMachine::where('manuf_gacha_title_id', $gacha_title->id)
        ->whereHas('gacha', function ($query) use ($gacha_key) {
            $query->where('key', $gacha_key)
                ->whereNotNull('published_at');
        })
        ->first();


        if( ! $machine ){
            $message =  !$gacha_key 
            ? 'ガチャマシーンが選択されていません。' 
            : 'このガチャマシーンを選択することはできません。';
        }
        

        # エラーメッセージ
        $alert_array = ! $machine
        ? ['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']
        : null ;

        
        return [$machine, $alert_array];
    }




}