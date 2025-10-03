<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　お知らせ　リクエスト 
| =============================================
*/
class AdminInfomationRequest extends FormRequest
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
            'title'        => ['required',],
            'body'         => ['required',],
            'image'        => ['file','max:10000','mimes:jpeg,png,jpg'],
            'is_slide'     => ['required',],
            'published_at' => ['required',],
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
            'title'        => '題名',
            'body'         => '本文',
            'image'        => '画像',
            'is_slide'     => 'スライドの表示有無',
            'published_at' => '公開日時',
        ];
    }



    /**
     * カスタムバリデーションの追加
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            # フォームから受け取ったエンコードされた 'title' をデコード
            $decode = urldecode( $this->input('title') );

            # デコード後の 'title' が140文字を超える場合はエラーを追加
            if (mb_strlen($decode) > 140) {
                $validator->errors()->add('title', '題名は140文字以内でなければなりません。');
            }
        });
    }

}
