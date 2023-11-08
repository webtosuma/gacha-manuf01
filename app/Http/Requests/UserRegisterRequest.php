<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
        return [
            'name'     => ['required', 'string', 'max:140'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'regex:/^[a-zA-Z0-9]{8,20}$/', 'confirmed'],
        ];

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
