<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  Admin　スポンサー 広告　リクエスト
| =============================================
*/
class AdminSpnsorAdRequest extends FormRequest
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
        // dd( $this->all() );

        return [
            'title'   => ['required','max:140',],
            'movie'   => ['required','file',],
            'gacha_id'=> ['required',],
            'sponsor_id'=>['required',],
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
            'title'   => '広告タイトル',
            'movie'   => '動画ファイル',
            'gacha_id'=> 'ガチャ',
            'sponsor_id'=>'スポンサー',
            ];
    }
}
