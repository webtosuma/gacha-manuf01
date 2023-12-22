<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　商品 リクエスト
| =============================================
*/
class AdminPrizeRequest extends FormRequest
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
            'category_id'  => ['required','max:140',],
            // 'image'        => ['required','file','max:10000','mimes:jpeg,png,jpg'], //イメージ画像
            'image'        => ['required','file',], //イメージ画像
            'code'         => ['required'],
            'name'         => ['required','max:140',],
            'rank_id'      => ['required','max:140',],
            'point'        => ['required','integer','min:0'],
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
            'category_id'  => 'カテゴリー',
            'code'         => '商品コード',
            'name'         => '商品名前',
            'image'        => '商品画像',
            'rank_id'      => '評価ランク',
            'point'        => '交換ポイント値',
        ];
    }
}
