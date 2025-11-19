<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gacha;
/*
| =============================================
|  アンケート コントローラー
| =============================================
*/
class SurveyController extends Controller
{
    /**
     * 回答ページ(POST)
     *
     * @param \Illuminate\Http\Request $request
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function answering(Request $request, $category_code, Gacha $gacha, $key)
    {
        # キーのチェック
        if( $gacha->key!=$key || !$gacha->published_at ){ return \App::abort(404); }

        # PLAYカウント
        $session_key = 'survey.answering.play_count';
        $play_count  = $request->play_count ?? ( session()->get($session_key) ?? 100);
        session()->put( $session_key, $play_count );//sessionに値を保存

        # [ルーティング]ガチャPLAY
        $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key];
        $r_action = route('gacha.play',$params);

        return view( 'survey.answering', compact('gacha','play_count','r_action'));
    }



}
