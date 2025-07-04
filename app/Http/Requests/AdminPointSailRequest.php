<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　販売ポイント　リクエスト
| =============================================
*/
class AdminPointSailRequest extends FormRequest
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
            'value'  => ['required','integer','min:0',],
            'price'  => ['required','integer','min:0'],
            'service'=> ['integer','min:0'],
            'is_published' => ['required','in:0,1'],
            // 'stripe_id' => ['required','max:140',],
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
            'value'  => '付与ポイント数',
            'price'  => 'ポイント販売価格',
            'service'=> 'お得分',
            //'is_subscription' => 'サブスクリプションか否か',
            'is_published'    => '公開設定',
            'stripe_id' => 'Stipeの商品ID',//
        ];
    }
}
