<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
/**
 * =========================================
 *  ユーザー情報更新　リクエスト
 * =========================================
*/
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name'     => ['required', 'string', 'max:140'],
            'email'    => ['required', 'email', 'unique:users'],
        ];


        # 前回のメールアドレスの重複除去
        $user = Auth::user();
        $request = $this->all(); //フォームの入力値をすべて取得
        if( isset($request['email']) && ($user->email === $request['email']) )
        {
            $rules['email'] = ['required', 'email'];
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
            'name'     => 'アカウント名',
            'email'    => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }

}
