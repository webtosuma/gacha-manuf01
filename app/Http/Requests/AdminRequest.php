<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin;
/**
 * =========================================
 *  サイト管理者　リクエスト
 * =========================================
*/
class AdminRequest extends FormRequest
{
    public function authorize(){ return true;}

    public function rules()
    {
        $rules = [
            'name' => ['required','max:140',],
            'email' => 'email|unique:users',
            'password' => 'regex:/^[!-~]{8,100}+$/|confirmed',
        ];


        # フォームの入力値をすべて取得
        $request = $this->all();

        # 管理者修正、メールアドレスの重複登録不可を解除
        if( $this->_method=='PATCH' )
        {
            if( !isset($request['name']) )
            {
                $rules['name'] = '';
            }

            $admin = Admin::find($request['admin_id']);
            if( isset($request['email']) && ($admin->email === $request['email']) )
            {
                $rules['email'] = ['email'];
            }

            // if( !isset($request['email']) )
            // {
            //     $rules['email'] = '';
            // }
        }


        return $rules;
    }

    /**
     * パラメーターの日本語表記
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'     => '名前',
            'email'    => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }
}
