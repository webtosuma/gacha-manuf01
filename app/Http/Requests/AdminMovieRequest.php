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
            // 'value'  => ['required','integer','min:0',],
            // 'price'  => ['required','integer','min:0'],
            // 'service'=> ['integer','min:0'],
            // 'is_published' => ['required','in:0,1'],
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
            'name'          => '動画名',
            'pc_storage'    => 'PC用動画・保存先',
            'mobile_storage'=> 'mobile用動画・保存先',
        ];
    }
}
