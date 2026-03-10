<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\UserAddress;
use App\Models\Admin;
/**
 * =========================================
 *  商品発送先の住所設定　コントローラー
 * =========================================
*/
class UserAddressController extends Controller
{
    /**
     * 商品発送先の住所一覧
     *
     * @return Response
     */
    public function index()
    {
        return  view('settings.user_address.index');
    }



    /**
     * 編集
     *
     * @param  UserAddress $user_address
     * @return Response
     */
    public function edit( UserAddress $user_address)
    {
        $user = Auth::user();
        if( $user_address->user_id != $user->id ){ return \App::abort(404); }


        # 前回のURLの保存
        {
            $current_url  = url()->current();  //前回のURL
            $previous_url = url()->previous(); //フォームページのURL
            $session_url  = session('previous_url');//セッション保存してURL(バリデーション対策)

            ## 同じURLならセッションのURLを使用
            if ($previous_url === $current_url)
            {
                $redirectUrl = $session_url ?? null;
            }
            ## 違うなら前回URLを使用
            else
            {
                $redirectUrl = $previous_url;
            }

            ## セッションに保存
            session(['previous_url' => $redirectUrl]);
        }


        return  view('settings.user_address.edit',compact(
            'user_address','previous_url'
        ));
    }



    /**
     * 更新
     *
     * @param  Request $request
     * @param  UserAddress $user_address
     * @return Response
     */
    public function update( Request $request, UserAddress $user_address )
    {

        # サイト管理者へメール送信
        {
            $user = $user_address->user;

            # メール受取り設定の管理者ユーザーの取得
            $admins = Admin::where('get_mail',1)->get();


            if($user_address->shipped_waiting_count>0)
            {
                # メールの送信
                foreach ($admins as $admin) {

                    Mail::to( $admin->email ) //宛先
                    ->send(new \App\Mail\SendHtmlMailMailable([
                        'inputs' => compact('user_address','user') , //入力変数
                        'view'   => 'emails.user_address.admin' ,    //テンプレート
                        'subject'=> 'お客様の発送受付中の発送住所が更新されました' , //件名
                    ]) );
                }

            }
        }


        return redirect($request->previous_url)
        ->with(['alert-warning'=>'商品発送先の住所を更新しました']);
    }




}
