<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
/*
| =============================================
|  認証情報(User) コントローラー
| =============================================
*/
class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | パスワード変更API
    |--------------------------------------------------------------------------
    */

        /**
         * パスワード変更 ステップ01(reset_pass_step01)
         *
         * @param \Illuminate\Http\Request $request
         * @return JSON
        */
        public function reset_pass_step01(Request $request)
        {

            /* 求職者として登録されているメールアカウントが存在するとき */
            $user = \App\Models\User::where('email', $request->email)->first();


            /* アカウントが存在しないとき */
            if( !$user  ){
                # エラーJSONデータを返す
                return response()->json([
                    'errors' => ['email' => 'このメールアドレスは登録されておりません。']
                ],422);
            }

            # 認証番号メールの送信(メール送信は1回のみ)
            $verification_code = SendMailController::SendVerifEmail( $request );

            return response()->json([
                'message' => 'reset_pass_step01 ok!',
                'verification_code' => $verification_code,
            ]);
        }


        /**
         * パスワード変更 ステップ02(reset_pass_step02)
         *
         * @param \Illuminate\Http\Request $request
         * @return JSON
        */
        public function reset_pass_step02(Request $request)
        {
            # 入力データに、DBへ保存した認証コードを追加
            $user = \App\Models\User::where('email', $request->email)->first();
            $request_all = $request->all();


            # 入力内容のバリデーション
            $rules = [
                'reset_pass_code' => ['required','confirmed'],
                'password' => ['required','confirmed','regex:/^[0-9a-zA-Z]{8,20}/','max:20'],
            ];
            $messages = [
                '*.required' => '入力されていません。',
                'reset_pass_code.confirmed' => '認証コードが正しくありません。',
                'password.confirmed' => '確認用パスワードと入力が異なります。',
                'password.regex' => '8文字以上20文字以下の半角英数字で入力して下さい。',
                'password.max' => '8文字以上20文字以下の半角英数字で入力して下さい。',
            ];
            $validator = Validator::make($request_all, $rules, $messages,);


            // バリデーションエラーレスポンス
            if( $validator->fails() ){
                return response()->json([ 'errors' => $validator->errors(), 'request_all'=>$request->all() ], 422);
            }


            # (バリデーション成功なら)パスワードを保存
            $user->update([ 'password' => Hash::make( $request->password ) ]);


            # 完了メールの送信
            SendMailController::UserUpdatePassword( $request );


            # バリデーション成功レスポンス
            return response()->json([ 'message' => 'reset_pass_step02 ok!', ]);
        }
}
