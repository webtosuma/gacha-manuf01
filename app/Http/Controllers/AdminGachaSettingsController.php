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


        # レインボー設定データ取得
        $rainbows = in_array( config('app.client'), ['cardfesta'] )
        ? \App\Models\Text::getRainbow() : null;


        return view('admin.gacha.settings.edit_list', compact('data','rainbows'));
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



    /**
     * レインボー 更新
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_rainbow( Request $request )
    {

        $now = now()->format('Y-m-d\TH:i');


        # 更新パラメーター
        switch ($request['type'])
        {
            # すぐに開始
            case 'start_now':
                $bodys = [
                    'rainbow_start_at' => $now,
                    'rainbow_end_at'   => null,
                ];
                break;

            # すぐに終了
            case 'end_now':
                $bodys = [
                    'rainbow_start_at' => $request['rainbow_start_at'],
                    'rainbow_end_at'   => $now,
                ];
                break;

            # 予約
            default:
                $bodys = [
                    'rainbow_start_at' => $request['rainbow_start_at'],
                    'rainbow_end_at'   => $request['rainbow_end_at'],
                ];
                break;
            /* */
        }

        foreach ($bodys as $type => $body)
        {
            # 型チェック
            $body =  preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $body) ? $body : null;

            # 保存データ
            $inputs = compact( 'type', 'body' );

            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();

            # DBデータの削除・更新・新規登録
            if( $text && !$body ){

                $text->delete();

            }else if( $text ){

                $text->update( $inputs );

            }else if( $body ){

                $text = new Text($inputs);
                $text->save();

            }
        }

        # 更新キーワード
        $type='rainbow_update';
        $type_label='レインボー設定';

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $type .'.update' );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.gacha.settings.edit_list')
        ->with(['alert-warning'=> $type_label.'を更新しました。']);
    }


}
