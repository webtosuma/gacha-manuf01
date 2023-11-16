<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  ユーザーアドレス レクエスト
| =============================================
*/
class UserAddressApiRequest extends FormRequest
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
            'name' => ['required','max:140'],
            'tell' => ['regex:/^0([0-9]{9,10})$/'],
            'postal_code' => ['required','regex:/^^\d{7}$/'],
            'todohuken'   => ['required','max:140'],
            'shikuchoson' => ['required','max:140'],
            'number'      => ['required','max:140'],
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
            'name' => '宛名',
            'tell' => '電話番号',

            'postal_code' => '郵便番号',
            'todohuken'   => '都道府県',
            'shikuchoson' => '市町村',
            'number'      => '丁目・番地',
        ];
    }
}
