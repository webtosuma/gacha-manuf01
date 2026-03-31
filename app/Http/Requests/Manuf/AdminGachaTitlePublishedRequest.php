<?php

namespace App\Http\Requests\Manuf;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  Manufacturer/Admin : ガチャ(タイトル) 公開設定 リクエスト
| =============================================
*/
class AdminGachaTitlePublishedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {
        $rules = [
            'estimated_shipping_at' => ['nullable', 'date'],
            'sales_start_at'        => ['nullable', 'date'],
            'sales_end_at'          => ['nullable', 'date', 'after_or_equal:sales_start_at'],
            'published_start_at'    => ['nullable', 'date'],
            'published_end_at'      => ['nullable', 'date', 'after_or_equal:published_start_at'],
        ];

        # ルールの追加(入力必須)
        if (!empty($this->published_start_at))
        {
            $rules['estimated_shipping_at'][] = 'required';
            $rules['sales_start_at'][]        = 'required';
            $rules['sales_end_at'][]          = 'required';
        }

        return $rules;
    }


    /**
     * パラメーターの日本語表記
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'estimated_shipping_at' => '発送予定日',
            'sales_start_at'        => '販売開始日',
            'sales_end_at'          => '販売終了日',
            'published_start_at'    => '公開開始日',
            'published_end_at'      => '公開終了日',
        ];
    }



    public function messages(): array
    {
        $messages = [
            'estimated_shipping_at.required' => '公開の場合、発送予定日は必須項目です',
            'sales_start_at.required'        => '公開の場合、販売開始日は必須項目です',
            'sales_end_at.required'          => '公開の場合、販売終了日は必須項目です',
        ];
        return $messages;
    }


}
