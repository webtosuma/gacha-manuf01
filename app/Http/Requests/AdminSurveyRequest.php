<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
| Admin アンケート　リクエスト
| =============================================
*/
class AdminSurveyRequest extends FormRequest
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
            'title'              => ['required','max:140'],
            'encode_title'       => ['required',],
            // 'resume_text'        => ['required',],
            // 'encode_resume_text' => ['required',],
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
            'title'              => 'タイトル',
            'resume_text'        => '説明文',
            'encode_title'       => 'エンコードタイトル',
            'encode_resume_text' => 'エンコード説明文',
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
