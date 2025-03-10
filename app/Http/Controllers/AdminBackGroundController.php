<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * =========================================
 *  サイト管理者　サイト背景の管理コントローラー
 * =========================================
*/
class AdminBackGroundController extends Controller
{
    /* ストレージ保存パス */
    public static function storagePath(){ return 'upload/back_ground/paths.json'; }

    /* デフォルトデータ */
    public static function  defaultData(){
        return [
            'bg_top'    => 'upload/gacha_category/bg_image/all.jpg' ,
            'bg_sub'    => 'site/image/bg01.jpg' ,
            'bg_result' => 'site/image/gacha/bg_result.jpg',
        ];

        // return [
        //     'bg_top'    => 'site/image/bg01.jpg' ,
        //     'bg_sub'    => 'site/image/bg02.jpg' ,
        //     'bg_result' => 'site/image/bg04.jpg' ,
        // ];

    }

    /* 各背景画像パス */
    public static function  getBgTop(){
        $bg_paths= Method::getStorageObjData( self::storagePath() );
        $bg_paths = $bg_paths ?? self::defaultData();
        return asset( 'storage/'.$bg_paths['bg_top'] );
    }
    public static function  getBgSub(){
        $bg_paths= Method::getStorageObjData( self::storagePath() );
        $bg_paths = $bg_paths ?? self::defaultData();
        return asset('storage/'.$bg_paths['bg_sub'] );
    }
    public static function  getBgResult(){
        $bg_paths= Method::getStorageObjData( self::storagePath() );
        $bg_paths = $bg_paths ?? self::defaultData();
        return asset('storage/'.$bg_paths['bg_result'] );
    }



    /**
     * 一覧
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        # 編集ページへリダイレクト
        return redirect()->route('admin.back_ground.edit');
    }




    /**
     * 編集(すべて)
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        # 連想配列データ(Object)をストレージより取得
        $bg_paths= Method::getStorageObjData( self::storagePath() );
        $bg_paths = $bg_paths ?? self::defaultData();

        return view('admin.back_ground.edit',$bg_paths);
    }




    /**
     * 更新(すべて)
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        # 連想配列データ(Object)をストレージより取得
        $bg_paths = Method::getStorageObjData( self::storagePath() );
        $bg_paths = $bg_paths ?? self::defaultData();


        # ストレージ画像ファイルの更新
        foreach ($bg_paths as $param => $bg_path) {
            $dir = 'upload/back_ground/'.$param;        //保存先ディレクトリ
            $request_file    = $request->file($param);     //画像のリクエスト
            $old_image_path  = $bg_path;                   //更新前の画像パス
            $image_dalete    = $request[$param.'_dalete']; //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;  //コピー用画像パス

            $bg_paths[$param] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth) ?? '';
        }

        # 連想配列データ(Object)をストレージ保存
        $put = Method::putStorageObjData( $this->storagePath(), $bg_paths );

        # 操作ログの更新
        AdminLogController::createLog( 'back_ground.edit' );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.back_ground.edit')
        ->with(['alert-warning'=>'サイト背景を更新しました。']);
    }
}
