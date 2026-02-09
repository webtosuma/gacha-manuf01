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
            // 'image'         => ['required','file','max:10000','mimes:jpeg,png,jpg'], //イメージ画像
            'image'         => ['required','file',],            //イメージ画像
            'default_name'  => ['required','max:140',],         //ガチャ名(エンコード除外)
            'one_play_point'=> ['required','integer','min:0'],  //ガチャの種類
            'type'          => ['required',],                   //ガチャの種類
        ];

        # 更新時のルール
        if($this->_method=='PATCH'){
            $rules['image'] = ['file','max:10000','mimes:jpeg,png,jpg']; //イメージ画像
        }
        # 限定回数のルール
        if( in_array( $this->type, ['n_time','n_oneday'] )  ){
            $rules['type_n_count']  = ['required', 'integer', 'min:0'];//限定回数(n回限定ガチャ用)
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
            'default_name'   => 'ガチャ名',
            'image'          => 'トップ画像',
            'one_play_point' => '1回のガチャに必要なポイント',
            'type'           => 'ガチャの種類',
            'type_n_count'   => '限定回数',
        ];
    }



    /**
     * オリジナルバリデーションメッセージ
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'type_n_count.required' => '「⚪︎回限定」を指定しているときは、限定回数の入力は必須です。',
        ];
    }


}
