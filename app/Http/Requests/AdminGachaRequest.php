<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　ガチャ　リクエスト
| =============================================
*/
class AdminGachaRequest extends FormRequest
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
            'category_id'   => ['required','max:140',],
            'image'         => ['required','file','max:10000','mimes:jpeg,png,jpg'], //イメージ画像
            'name'          => ['required','max:140',],
            'one_play_point'=> ['required','integer','min:0'],

        ];

        // 更新時のルール
        if($this->_method=='PATCH'){
            $rules['image'] = ['file','max:10000','mimes:jpeg,png,jpg']; //イメージ画像
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
            'category_id'    => 'カテゴリー',
            'name'           => 'ガチャ名',
            'image'          => 'トップ画像',
            'one_play_point' => '1回のガチャに必要なポイント',
        ];
    }
}
