<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
/**
 * =========================================
 *  会員情報設定　コントローラー
 * =========================================
*/
class SettingsController extends Controller
{
    /**
     * アカウント情報変更
     *
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function acount_update( UpdateUserRequest $request )
    {
        $user = Auth::user();

        # 更新情報
        $inputs = $request->only(
            'name',  //名前
            'email', //メールアドレス
            'image', //イメージ画像
        );

        # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/user/image/';             //保存先ディレクトリ
            $request_file    = $request->file('image');     //画像のリクエスト
            $old_image_path  = $user ? $user->image: null; //更新前の画像パス
            $image_dalete    = $request->image_dalete;      //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;       //コピー用画像パス
            $inputs['image'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);
        //

        # 求職者情報の保存
        $user->update($inputs);
        $request->session()->regenerateToken();// 二重送信防止

        return redirect()->route('settings')
        ->with(['alert-warning'=>'アカウント情報を更新しました']);
    }



    /*
    |--------------------------------------------------------------------------
    | クレジットカード情報設定
    |--------------------------------------------------------------------------
    */

        /**
         * クレジット情報設定
         *
         * @param \App\Http\Requests\UpdateUserRequest $request
         * @return \Illuminate\Http\Response
         */
        public function credit_card()
        {
            $cardList = PointSailController::UserCardList();//ユーザーが登録したカード一覧の取得
            return  view('settings.credit_card', compact('cardList'));
        }


        /**
         * クレジットカード　新規登録
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\View\View
        */
        public function credit_card_create( Request $request )
        {
            # 新規作成メソッド
            PointSailController::MethodCreate( $request );


            # 設定ページへリダイレクト
            return redirect()->route('settings.credit_card');

        }


        /**
         * クレジットカード　削除
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\View\View
        */
        public function credit_card_destroy( Request $request )
        {
            # 削除メソッド
            PointSailController::MethodDestory( $request );

            # 設定ページへリダイレクト
            return redirect()->route('settings.credit_card');
        }
    //
}
