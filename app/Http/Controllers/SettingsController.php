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
            'twitter_id',//X(旧twitter)ID
        );

        # 誕生日
        if( config('app.min_age') )
        {
            $inputs['birthday'] = isset($request->birthday_y) && isset($request->birthday_m) && isset($request->birthday_d)
            ? $request->birthday_y.'-'.$request->birthday_m.'-'.$request->birthday_d
            : null ;//年齢設定なしのとき
        }


        # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/user/image/';             //保存先ディレクトリ
            $request_file    = $request->file('image');     //画像のリクエスト
            $old_image_path  = $user ? $user->image: null; //更新前の画像パス
            $image_dalete    = $request->image_dalete;      //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;       //コピー用画像パス
            $inputs['image'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);
        //

        # 二段階認証の利用
        $inputs['is_tfa'] = $request->is_tfa ? true :false ;


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
            return redirect()->route('point_sail.customer_portal');
        }



    /*
    |--------------------------------------------------------------------------
    | メール受信設定
    |--------------------------------------------------------------------------
    */
        /**
         * メール受信設定変更
         *
         * @param \App\Http\Requests\Request $request
         * @return \Illuminate\Http\Response
         */
        public function email_reception_update( Request $request )
        {
            $user = Auth::user();
            $get_email = $request->get_email ? 1 : 0;

            # 求職者情報の保存
            $user->update( compact('get_email') );
            $request->session()->regenerateToken();// 二重送信防止

            return redirect()->route('settings')
            ->with(['alert-warning'=>'メール受信設定を更新しました']);
        }


    /*
    |--------------------------------------------------------------------------
    | 誕生日登録
    |--------------------------------------------------------------------------
    */
        /**
         * 誕生日変更
         *
         * @param \App\Http\Requests\UpdateUserRequest $request
         * @return \Illuminate\Http\Response
         */
        public function birthday_update( UpdateUserRequest $request )
        {
            $user = Auth::user();

            # 入力値の編集
            $inputs = [];
            $inputs['birthday'] = isset($request->birthday_y) && isset($request->birthday_m) && isset($request->birthday_d)
            ? $request->birthday_y.'-'.$request->birthday_m.'-'.$request->birthday_d
            : null ;


            # 更新情報
            $user->update($inputs);
            $request->session()->regenerateToken();// 二重送信防止


            # 年齢チェック NG
            if( $user->age < config('app.min_age') ){
                return redirect()->route('settings.age.restrictedy');
            }

            # 年齢チェック OK
            return redirect()->route('settings.age.birthday.comp');
        }
    //
}
