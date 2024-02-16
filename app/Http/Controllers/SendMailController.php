<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use \App\Models\Admin;
/*
|--------------------------------------------------------------------------
| メール送信コントローラー　※コントローラー内で利用
|--------------------------------------------------------------------------
*/
class SendMailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ポイント購入完了
    |--------------------------------------------------------------------------
    */
        /**
         * ポイント購入[完了]
         *
         * @param \Illuminate\Http\Request $request
         * @return String $verification_code
        */
        public static function PaymentComp( $request )
        {
            $user = $request->user;
            $point_sail = $request->point_sail;
            $inputs = compact('user','point_sail');


            # ユーザーへ送信
            // if($user->get_email){

            //     Mail::to( $user->email ) //宛先
            //     ->send(new \App\Mail\SendHtmlMailMailable([
            //         'inputs'  => $inputs, //入力変数
            //         'view'    => 'emails.payment_comp' , //テンプレート
            //         'subject' => $point_sail->value.'ptのご購入、ありがとうございます。', //件名
            //     ]) );

            // }


            # サイト管理者へ送信
            $subject = 'ID:'.$user->id.' '.$user->name.'様が、'.$point_sail->value.'pt購入しました';
            Mail::to( $request->email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs'  => $inputs, //入力変数
                'view'    => 'emails.admin_payment_comp' , //テンプレート
                'subject' => $subject , //件名
            ]) );
        }

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
                $admins = Admin::where('get_mail',1)->get();

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
                $admins = Admin::where('get_mail',1)->get();

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
         * ユーザー登録01[メール認証]
         *
         * @param \Illuminate\Http\Request $request
         * @return String $verification_code
        */
        public static function UserAuthRegister01( $request )
        {
            // 認証番号が生成済みであれば、処理を終了
            if( $request->created_verification_code ){ return $request->created_verification_code; }

            // 認証番号の生成
            $verification_code = '';
            for ($i=0; $i < 6; $i++) {
                $rand = mt_rand(1,9);
                $verification_code = $verification_code.$rand;
            }


            // 認証番号メールの送信
            Mail::to( $request->email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs' => compact('verification_code') , //入力変数
                'view' => 'emails.user_register01_verification' , //テンプレート
                'subject' => '会員登録認証コード'.$verification_code , //件名
            ]) );


            # 認証コードを返す
            return $verification_code;
        }



        /**
         * ユーザー登録02[登録完了]
         *
         * @param \App\Models\User $user
         * @return Void
        */
        public static function UserAuthRegiste( $user )
        {

            # 求職者へメール送信

                Mail::to( $user->email ) //宛先
                ->send(new \App\Mail\SendHtmlMailMailable([
                    'inputs' => null , //入力変数
                    'view' => 'emails.worker_register02_completion' , //テンプレート
                    'subject' => '会員登録が完了いたしました。' , //件名
                ]) );


            # サイト管理者へメール送信

                // メール受取り設定の管理者ユーザーの取得
                $admins = \App\Models\Admin::where('get_mail',1)->get();

                // 入力パラメーター
                $inputs = ['user'=>$user];

                # メールの送信
                // foreach ($admins as $admin) {

                //     Mail::to( $admin->email ) //宛先
                //     ->send(new \App\Mail\SendHtmlMailMailable([
                //         'inputs' => $inputs , //入力変数
                //         'view'   => 'emails.admin_user_auth_register' , //テンプレート
                //         'subject'=> '会員登録を受け付けました。' , //件名
                //     ]) );
                // }

            //
            # テンプレートの表示
            // return view('emails.admin_worker_auth_register', $inputs );
        }




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



        /**
         * ユーザー退会処理
         *
         * @param Array $inputs
         * @return Void
        */
        public static function UserDestroy($inputs)
        {

            # お問い合わせ入力者へメール送信

                Mail::to( $inputs['email'] ) //宛先
                ->send(new \App\Mail\SendHtmlMailMailable([
                    'inputs' => $inputs , //入力変数
                    'view' => 'emails.user_destroy' , //テンプレート
                    'subject' => '退会処理が完了しました。' , //件名
                ]) );


            # サイト管理者へメール送信

                // メール受取り設定の管理者ユーザーの取得
                $admins = Admin::where('get_mail',1)->get();

                # メールの送信
                foreach ($admins as $admin) {

                    Mail::to( $admin->email ) //宛先
                    ->send(new \App\Mail\SendHtmlMailMailable([
                        'inputs' => $inputs , //入力変数
                        'view'   => 'emails.admin_user_destroy' , //テンプレート
                        'subject'=> '退会を受け付けました。' , //件名
                    ]) );
                }

            //
        }

    //
}
