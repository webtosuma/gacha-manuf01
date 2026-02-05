<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
/**
 * =========================================
 *  ユーザー登録　リクエスト
 * =========================================
*/
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

        $rules = [
            'name'     => ['required', 'string', 'max:140'],
            'email'    => [
                'required', 'email', 'unique:users',
                'not_regex:' . $this->emailBlockRegex(),//指定メールアドレスの拒否
            ],
            'password' => ['required', 'regex:/^[a-zA-Z0-9]{8,20}$/', 'confirmed'],
        ];

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

    public function messages()
    {
        return [
            'email.not_regex' => 'このメールアドレスは利用できません。',
        ];
    }
    /**
     * メール登録拒否する文字列の配列を返す
     */
    protected function blockedEmailPatterns(): array
    {
        return [
            '@nanana.uk',
            '@teml.net',
            '@drmail.in',
            '@dakaka.org',
            '@pngk.uk',
            '@momoi.uk',
            '@boxfi.uk',
            '@addrin.uk',
            '@mama3.org',

        ];
    }

    /**
     * メール登録拒否するパターンを regex 文字列に変換して返す
     */
    protected function emailBlockRegex(): string
    {
        $escaped = array_map(function ($str) {
            return preg_quote($str, '/');
        }, $this->blockedEmailPatterns());

        return '/(' . implode('|', $escaped) . ')/i';
    }


}
