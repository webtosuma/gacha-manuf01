<?php

namespace App\Http\Requests\Manuf;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ManufGachaTitlePrize;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル 商品 リクエスト
| =============================================
*/
class AdminGachaTitlePrizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        # 商品ID
        $titlePrizeId = $this->route('title_prize')?->id;
        $titlePrize   = ManufGachaTitlePrize::find($titlePrizeId);
        $prize_id = $titlePrize?->prize_id;


        $rules = [

            'image' => [ 'nullable', 'image', 'max:1024', ],
            'name'  => [ 'required', 'string', 'max:140', 'regex:/^[^\x{1F300}-\x{1FAFF}]+$/u', ],

            'code'  => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\-\_\*\+\.\,\!\?\#\$\%\&\~\|\^\@\;\:\(\)\[\]\{\}\/\s]+$/',
                Rule::unique('prizes', 'code')->ignore($prize_id),//コードの重複不可
            ],

            'rank_id' => [
                'required',
                'exists:prize_ranks,id',
            ],

            'default_description' => [ 'nullable', 'string',],
            'is_published' => [ 'required', 'in:0,1',],
        ];


        # 新規登録のルール
        if( $this->_method!='PATCH' ){
            $rules['image'][] = 'required'; //入力必須
        }


        return $rules;
    }



    /**
     * 項目名（attributes）
     */
    public function attributes(): array
    {
        return [
            'image'               => '商品画像',
            'name'                => '商品名',
            'code'                => '商品コード',
            'rank_id'             => '評価ランク',
            'discription'         => '説明文',//エンコード
            'default_description' => '説明文',
            'is_published'        => '公開設定',
        ];
    }



    /** メッセージ */
    public function messages(): array
    {
        return [
            'name.regex' => ':attributeに絵文字は使用できません。',
            'code.regex' => ':attributeの形式が不正です。',
        ];
    }



    /** ラジオボタン未選択でも必ず値を入れるための保険 */
    protected function prepareForValidation()
    {
        if (!$this->has('is_published')) {
            $this->merge([ 'is_published' => 0 ]);
        }
    }


}
