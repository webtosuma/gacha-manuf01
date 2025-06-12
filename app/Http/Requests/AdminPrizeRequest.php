<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Prize;
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
            'category_id'  => ['required'],
            // 'image'        => ['required','file','max:10000','mimes:jpeg,png,jpg'], //イメージ画像
            'image'        => ['required','file',], //イメージ画像
            // 'code'         => ['required'],
            'code' => [
                'required',
                'max:140',
                'regex:/^[a-z0-9 \-\_\*\+\.,!?#\$%&~\|\^@;:\(\)\[\]\{\}\/]+$/i',
                'unique:prizes',
            ],
            'name'         => ['required'],
            'rank_id'      => ['required'],
            'point'        => ['required','integer','min:0'],
        ];


        // 更新時のルール
        if($this->_method=='PATCH'){
            $rules['image'] = ['file','max:10000','mimes:jpeg,png,jpg']; //イメージ画像
        }

        # 重複ルールの条件解除
        $prize = Prize::find($this->prize_id);
        if( isset($prize) && $prize->code === $this->code )
        {
            $rules['code'] = ['required','max:140',];
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
