<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Text;
/*
| =============================================
|  フッターメニュー コントローラー
| =============================================
*/
class FooterMenuController extends Controller
{
    /* 共通メソッド */
    public function commonMethod($type,$revision_date=null)
    {
        # バックナンバー
        $texts =  Text::forType($type)->get();

        # 文書データの取得
        $text = Text::forType($type, $revision_date)->first();
        if (!$text) { abort(404); }// 見つからなければ 404

        # 本文・制定日・改訂日フォーマット enactmented_at_format　
        $body = $text->body_text;
        $body_head = $text->enactmented_at==$texts[$texts->count()-1]->enactmented_at ? '制定日' : '改訂日';
        $enactmented_at_format = $body_head.$text->enactmented_at_format;


        return view('footer_menu.'.$type.'.db_index',compact(
            'texts','body','enactmented_at_format',
        ));
    }



    /**
     * ガイド(guide)
     *
     * @return \Illuminate\Http\Response
     */
    public function guide()
    {
        # タイプキー
        $type = 'guide';

        # 共通メソッド
        return self::commonMethod($type);
    }



    /**
     * 利用規約(trems)
     *
     * @param String $revision_date
     * @return \Illuminate\Http\Response
     */
    public function trems( $revision_date=null )
    {
        # タイプキー
        $type = 'trems';

        # 共通メソッド
        return self::commonMethod($type,$revision_date);
    }



    /**
     * プライバシーポリシー(privacy_policy)
     *
     * @param String $revision_date
     * @return \Illuminate\Http\Response
     */
    public function privacy_policy( $revision_date=null )
    {
        # タイプキー
        $type = 'privacy_policy';

        # 共通メソッド
        return self::commonMethod($type,$revision_date);
    }



    /**
     * 特定商取引法に基づく表記(tradelaw)
     *
     * @param String $revision_date
     * @return \Illuminate\Http\Response
     */
    public function tradelaw( $revision_date=null )
    {
        # タイプキー
        $type = 'tradelaw';

        # 共通メソッド
        return self::commonMethod($type,$revision_date);
    }



}
