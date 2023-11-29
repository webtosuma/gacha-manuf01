<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * =========================================
 *  お問い合わせ　リクエスト
 * =========================================
*/
class ContactRequest extends FormRequest
{
    public function authorize(){ return true;}

    public function rules()
    {
        return [
            'name'  => ['required','max:140',],
            'email' => ['required','email'],
            'tell'  => ['required','regex:/^0([0-9]{9,10})$/'],
            'body'  => ['required',],
            'agree' => ['required'],
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
            'name'  => '氏名',
            'email' => 'メール',
            'tell'  => '電話番号',
            'body'  => '本文',
            'agree' => 'プライバシーポリシー同意のチェック',
        ];
    }
}
