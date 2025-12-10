<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Text;
/*
| =============================================
|  Admin　文書設定 コントローラー
| =============================================
*/
class AdminTextUserRankController extends Controller
{
    /**
     * 編集
     *
     * @param  String $type
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $text_type = ['type'=>'user_rank','multiple'=>false,'textarea_rows'=>null, 'label'=>'会員ランク'];

        $types = [
            'user_rank_title',
            'user_rank_img01','user_rank_body01',
            'user_rank_img02','user_rank_body02',
        ];

        # データ取得
        $text_bodys = \App\Models\Text::getUserRank();

        return view('admin.text.edit', compact('text_bodys','text_type') );
    }



    /**
     * 更新
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request )
    {
        # 更新パラメーター
        $bodys = [
            'user_rank_title'  => $request['user_rank_title'],
            'user_rank_img01'  => $request['user_rank_img01'],
            'user_rank_body01' => $request['user_rank_body01'],
            'user_rank_img02'  => $request['user_rank_img02'],
            'user_rank_body02' => $request['user_rank_body02'],
        ];
        // dd($request->all());]
        // dd($bodys);

        foreach ($bodys as $type => $body)
        {
            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();

            # 保存データ
            $inputs = [
                'type' => $type,
                'body' => $body
            ];


            # title
            if(str_contains($type, 'title'))
            {
                # デコード処理（絵文字対策）・改行除去
                $inputs['body'] = urldecode( $body );

            }
            # ストレージ更新の処理（本文）body
            if (str_contains($type, 'body'))
            {
                # デコード処理（絵文字対策）・改行除去
                $inputs['body'] = urldecode( $body );

                $old_text = $text? $text->body: null;        //更新前のファイルパステキスト
                $new_text = $inputs['body'];                 //新しい入力テキスト
                $dir = 'upload/' .$type. '/body/'; //保存先ディレクトリ
                $inputs['body'] = Method::uploadStorageText($dir, $new_text, $old_text);

            }
            # 画像の保存
            if (str_contains($type, 'img'))
            {
                $dir = 'upload/' .$type.'/image/';             //保存先ディレクトリ
                $request_file    = $request->file($type);     //画像のリクエスト
                $old_image_path  = $text? $text->body: null; //更新前の画像パス
                $image_dalete    = $request[$type.'_dalete'];      //画像を削除するか否か

                $inputs['body'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete );

            }


            # DBデータの更新・新規登録
            if( !$text && !$inputs['body'] )
            {
                continue; //未入力
            }
            elseif( $text && !$inputs['body'] )
            {
                $text->delete(); //未入力
            }
            elseif( $text )
            {
                $text->update( $inputs );
            }
            else
            {
                $text = new Text($inputs);
                $text->save();
            }
        }


        # 更新キーワード
        $type='user_rank';
        $type_label='会員ランク';

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $type .'.update' );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.text')
        ->with(['alert-warning'=> $type_label.'を更新しました。']);

    }



}
