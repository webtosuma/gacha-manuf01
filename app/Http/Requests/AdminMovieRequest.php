<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMovieRequest extends FormRequest
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
            'name'          => ['required','max:140',],
            'pc_storage'    => ['file',],
            'mobile_storage'=> ['file',],
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
            'name'          => '演出動画名',
            'pc_storage'    => 'PC用動画・保存先',
            'mobile_storage'=> 'mobile用動画・保存先',
        ];
    }
}
