<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| メール送信コントローラー　※コントローラー内で利用
|--------------------------------------------------------------------------
*/
class SendMailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | お問い合わせ
    |--------------------------------------------------------------------------
    */
        /**
         * お問い合わせ[完了]
         *
         * @param \Illuminate\Http\Request $request
         * @return String $verification_code
        */
        public static function ContactCompletion( $request )
        {
            # お問い合わせ入力者へメール送信

                Mail::to( $request->email ) //宛先
                ->send(new \App\Mail\SendHtmlMailMailable([
                    'inputs' => $request->all() , //入力変数
                    'view' => 'emails.contact' , //テンプレート
                    'subject' => 'お問い合わせを受け付けました' , //件名
                ]) );


            # サイト管理者へメール送信

                // メール受取り設定の管理者ユーザーの取得
                $admins = \App\Models\Admin::where('get_mail',1)->get();

                # メールの送信
                foreach ($admins as $admin) {

                    Mail::to( $admin->email ) //宛先
                    ->send(new \App\Mail\SendAdminMailable([
                        'inputs' => $request->all() , //入力変数
                        'view'   => 'emails.admin_contact' , //テンプレート
                        'subject'=> 'お客様よりお問い合わせを受け付けました' , //件名
                    ]) );
                }

            //
        }



        /**
         * 企業登録お問い合わせ[完了]
         *
         * @param \Illuminate\Http\Request $request
         * @return String $verification_code
        */
        public static function CompanyContactPost( $request )
        {
            # お問い合わせ入力者へメール送信

                Mail::to( $request->email ) //宛先
                ->send(new \App\Mail\SendHtmlMailMailable([
                    'inputs' => $request->all() , //入力変数
                    'view' => 'emails.company_contact' , //テンプレート
                    'subject' => 'お問い合わせを受け付けました' , //件名
                ]) );


            # サイト管理者へメール送信

                // メール受取り設定の管理者ユーザーの取得
                $admins = \App\Models\Admin::where('get_mail',1)->get();

                # メールの送信
                foreach ($admins as $admin) {

                    Mail::to( $admin->email ) //宛先
                    ->send(new \App\Mail\SendHtmlMailMailable([
                        'inputs' => $request->all() , //入力変数
                        'view'   => 'emails.admin_contact' , //テンプレート
                        'subject'=> 'お客様よりお問い合わせを受け付けました' , //件名
                    ]) );
                }

            //
        }
    /*
    |--------------------------------------------------------------------------
    | 認証・変更
    |--------------------------------------------------------------------------
    */
        /**
         * メール認証
         *
         * @param \Illuminate\Http\Request $request
         * @return String $verification_code
        */
        public static function SendVerifEmail( $request )
        {
            # 認証番号が生成済みであれば、処理を終了
            if( $request->created_verification_code ){ return $request->created_verification_code; }

            # 認証番号の生成
            $verification_code = sprintf('%06d', mt_rand(0, 999999) );

            # 認証番号メールの送信
            Mail::to( $request->email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs' => compact('verification_code') , //入力変数
                'view' => 'emails.worker_register01_verification' , //テンプレート
                'subject' => '会員登録認証コード'.$verification_code , //件名
            ]) );


            # 認証コードを返す
            return $verification_code;
        }





        /**
         * メールアドレス変更
         *
         * @param \Illuminate\Http\Request $request
         * @return Void
        */
        public static function UserUpdateEmail( $request )
        {
            Mail::to( $request->email ) //宛先
            ->send(new \App\Mail\SendMailMailable([
                'inputs' => $request , //入力変数
                'view' => 'emails.reset_email02_verification' , //テンプレート
                'subject' => '【'.env('APP_NAME').'】メールアドレス変更が完了いたしました' , //件名
            ]) );
        }




        /**
         * パスワード変更
         *
         * @param \Illuminate\Http\Request $request
         * @return Void
        */
        public static function UserUpdatePassword( $request )
        {
            Mail::to( $request->email ) //宛先
            ->send(new \App\Mail\SendMailMailable([
                'inputs' => $request , //入力変数
                'view' => 'emails.reset_pass02_completion' , //テンプレート
                'subject' => '【'.env('APP_NAME').'】パスワード変更が完了いたしました' , //件名
            ]) );
        }




    //
}
