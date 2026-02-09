<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Text;
/*
| =============================================
|  Admin　ガチャ(一覧表示の設定) コントローラー
| =============================================
*/
class AdminGachaSettingsController extends Controller
{
    /**
     * ガチャ一覧の設定　編集
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_list()
    {
        $types = [

            # ガチャ販売機の画像を利用する
            'gacha_settings_card_image',
            # ガチャ販売機の頭部画像
            'gacha_settings_card_image_head',
            'gacha_settings_card_image_head_default',
            # ガチャ販売機の本体画像
            'gacha_settings_card_image_body',
            'gacha_settings_card_image_body_default',

            # ガチャの読み込み中動画
            'gacha_settings_loading_movie',
            'gacha_settings_loading_movie_path',
            'gacha_settings_loading_movie_path_default',

            # 限定ガチャのラベル表示
            'gacha_settings_type_label_image',
            # 限定ガチャのテキスト表示
            'gacha_settings_type_label_text',
            # デフォルトの表示サイズ
            'gacha_settings_size',

        ];

        $data = [];
        foreach ($types as $type) {
            $text = new Text();
            $data[$type] = $text[$type];
        }
        // dd($data['gacha_settings_type_label_text']);

        return view('admin.gacha.settings.edit_list', compact('data'));
    }



    /**
     * ガチャ販売機の画像設定 更新
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_card_image(Request $request)
    {
        $inputs = [
            # ガチャ販売機の画像を利用する
            'gacha_settings_card_image'      => $request->gacha_settings_card_image ?? '' ,
            # ガチャ販売機の頭部画像
            'gacha_settings_card_image_head' => $request->file('gacha_settings_card_image_head') ?? '' ,
            # ガチャ販売機の本体画像
            'gacha_settings_card_image_body' => $request->file('gacha_settings_card_image_body') ?? '' ,
        ];
        // dd($inputs);

        foreach ($inputs as $type => $body)
        {
            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();

            # 画像の保存
            if( $type!='gacha_settings_card_image' )
            {
                # ストレージ画像ファイルの更新（イメージ画像）
                $dir = 'upload/gacha/settings/card_image';      //保存先ディレクトリ
                $request_file    = $request->file($type);//画像のリクエスト
                $old_image_path  = $text ? $text->body : null; //更新前の画像パス
                $image_dalete    = isset($body) ? true : false ;//画像を削除するか否か

                $body = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete) ?? $old_image_path;
            }

            # 保存データ
            $params = [ 'type' => $type, 'body' => $body ];

            # DBデータの更新・新規登録
            if( $text ){
                $text->update( $params );
            }else{
                $text = new Text($params);
                $text->save();
            }
        }

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.settings.card_image.update' );

        $request->session()->regenerateToken();// 二重送信防止

        # リダイレクト
        return redirect()->route('admin.gacha.settings.edit_list')
        ->with(['alert-warning'=>'ガチャ販売機の画像設定を更新しました']);
    }



    /**
     * ガチャの読み込み中動画の設定 更新
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_loading_movie(Request $request)
    {
        $inputs = [
            'gacha_settings_loading_movie'      => $request->gacha_settings_loading_movie ?? '' ,
            'gacha_settings_loading_movie_path' => $request->file('gacha_settings_loading_movie_path') ?? '' ,
        ];

        foreach ($inputs as $type => $body)
        {

            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();

            # 動画の保存
            if( $type=='gacha_settings_loading_movie_path' )
            {
                # ストレージ画像ファイルの更新（イメージ画像）
                $dir = 'upload/gacha/settings/loading_movie_path';      //保存先ディレクトリ
                $request_file    = $request->file($type);//画像のリクエスト
                $old_image_path  = $text ? $text->body : null; //更新前の画像パス
                $image_dalete    = isset($body) ? true : false ;//画像を削除するか否か

                // dd($old_image_path);
                $body = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete) ?? $old_image_path;
            }

            # 保存データ
            $params = [ 'type' => $type, 'body' => $body ];

            # DBデータの更新・新規登録
            if( $text ){
                $text->update( $params );
            }else{
                $text = new Text($params);
                $text->save();
            }
        }

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.settings.loading_movie.update' );

        $request->session()->regenerateToken();// 二重送信防止


        # リダイレクト
        return redirect()->route('admin.gacha.settings.edit_list')
        ->with(['alert-warning'=>'ガチャの読み込み中動画の設定を更新しました']);
    }



    /**
     * ガチャのその他設定 更新
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_other(Request $request)
    {
        $inputs = [
            # 限定ガチャのラベル表示
            'gacha_settings_type_label_image' => $request->gacha_settings_type_label_image ?? '' ,
            # 限定ガチャのテキスト表示
            'gacha_settings_type_label_text'  => $request->gacha_settings_type_label_text ?? '' ,
            # デフォルトの表示サイズ
            'gacha_settings_size'             => $request->gacha_settings_size ,
        ];

        foreach ($inputs as $type => $body)
        {
            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();

            # 保存データ
            $params = [ 'type' => $type, 'body' => $body ];

            # DBデータの更新・新規登録
            if( $text ){
                $text->update( $params );
            }else{
                $text = new Text($params);
                $text->save();
            }
        }

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.settings.other.update' );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.gacha.settings.edit_list')
        ->with(['alert-warning'=>'ガチャのその他設定を更新しました']);
    }

}
