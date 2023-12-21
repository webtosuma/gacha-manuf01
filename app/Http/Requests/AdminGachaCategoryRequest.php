<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　ガチャのカテゴリー　リクエスト
| =============================================
*/
class AdminGachaCategoryRequest extends FormRequest
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
        $rules = [
            'name'     => ['required','max:140',],
            'code_name'=> ['required','max:140',],
            'image'    => ['file','max:10000','mimes:jpeg,png,jpg'], //イメージ画像
            'is_published'=> ['required',],
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
            'name'        => 'カテゴリー名',
            'code_name'   => 'コード',
            'bg_image'    => '背景画像',
            'is_published'=> '公開設定',
        ];
    }
}
