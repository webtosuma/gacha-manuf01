<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　文書設定 複数登録　リクエスト 
| =============================================
*/
class AdminTextMultipleRequest extends FormRequest
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
            'enactmented_at' => ['required'],
            'body'           => ['required'],
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
            'type'           => '種類',
            'enactmented_at' => '制定日・改訂日',
            'body'           => '本文',
        ];
    }
}
