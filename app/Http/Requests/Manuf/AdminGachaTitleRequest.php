<?php

namespace App\Http\Requests\Manuf;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  Manufacturer/Admin : ガチャ(タイトル) リクエスト
| =============================================
*/
class AdminGachaTitleRequest extends FormRequest
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

            /* 基本情報 */
            'default_name'=> ['required','max:140',],
            'category_id' => ['required',],
            'price'       => ['required', 'numeric'],
            'image_samune'=> ['required','file',],

            /* 詳細情報 */
            // 'default_description'     => ['max:140',],//説明文
            // 'default_set_contents'    => ['max:140',],//セット内容
            'default_prize_size'      => ['max:140',],//商品サイズ
            'default_prize_materials' => ['max:140',],//商品素材
            'default_age_range'       => ['max:140',],//対象年齢
            'default_copy_right'      => ['max:140',],//コピーライト

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

            /* 基本情報 */
            'default_name'   => 'タイトル名',
            'category_id'    => 'カテゴリー',
            'image_samune'   => 'サムネ画像',
            'price'          => '価格',

            /* 詳細情報 */
            'default_description'     => '説明文',
            'default_set_contents'    => 'セット内容',
            'default_prize_size'      => '商品サイズ',
            'default_prize_materials' => '商品素材',
            'default_age_range'       => '対象年齢',
            'default_copy_right'      => 'コピーライト',

        ];
    }




}
