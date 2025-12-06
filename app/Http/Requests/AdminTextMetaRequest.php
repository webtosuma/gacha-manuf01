<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　文書設定 メタ情報　リクエスト
| =============================================
*/
class AdminTextMetaRequest extends FormRequest
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
            'default_meta_title'       => ['required','max:140'],
            'default_meta_discription' => ['required','max:140'],
            'default_meta_keyword'     => ['required','max:140'],
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
            'default_meta_title'       => 'タイトル',
            'default_meta_discription' => 'サイト説明文',
            'default_meta_keyword'     => 'キーワード',
        ];
    }
}
