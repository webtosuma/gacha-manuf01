<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　文書設定(古物商営業許可)　リクエスト
| =============================================
*/
class AdminTextSbgLicenseRequest extends FormRequest
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
        $prefectures = [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
        ];

        // 配列 → 正規表現パターン文字列に変換
        $prefPattern = implode('|', $prefectures);

        return [
            'default_license_name'    => ['required','max:140'],
            'default_license_number'  => ['required', 'string', 'regex:/^\d{12}$/'],
            'license_commission'       => ['required','max:140'],

            // 'default_license_commission' => [
            //     'required', 'string', "regex:/^($prefPattern)公安委員会$/u",
            // ],

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
            'default_license_name'       => '法人(個人)名称',
            'default_license_number'     => '古物商許可番号',
            'default_license_commission' => '公安委員会の名称',
            'license_commission' => '公安委員会の名称',
        ];
    }
}
