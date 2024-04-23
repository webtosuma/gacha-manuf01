<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  Admin　スポンサー　リクエスト
| =============================================
*/
class AdminSpnsorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name'  => ['required',],
            'user_email' => ['required','email', 'unique:users,email'],
            'user_address_tell'=> ['required','regex:/^0([0-9]{9,10})$/'],
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
            'user_name'  => 'スポンサー名',
            'user_email' => 'メールアドレス',
            'user_address_tell'=> '電話番号',
        ];
    }
}
