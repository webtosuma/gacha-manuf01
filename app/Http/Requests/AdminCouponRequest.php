<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/*
| =============================================
|  サイト管理者　クーポン　リクエスト
| =============================================
*/
class AdminCouponRequest extends FormRequest
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
            'title'           => ['required','max:140',],
            'is_use_code'     => ['required'],

            'service'         => ['required','in:point,prize'],

            'user_type'       => ['required','in:user,all_user'],
            // 'is_count'        => ['required'],

            'is_expiration'   => ['required'],

            'is_published'    => ['required'],
        ];


        # フォームの入力値をすべて取得
        $request = $this->all();

        # サービスのルール追加
        switch ($request['service'])
        {
            case 'prize': $rules['prize_code'] = ['required','max:140',]; break;
            case 'point': $rules['point']      = ['required']; break;
        }


        # 利用回数制限のルール追加
        // if( $request['is_count'] )
        // {
        //     $rules['count']     = ['required'];
        //     $rules['user_type'] = ['required'];
        // }

        # 有効期限
        if( $request['is_expiration'] )
        {
            $rules['expiration_at'] = ['required'];
        }

        # 公開予約設定
        if( $request['is_published']==2 )
        {
            $rules['published_at'] = ['required'];
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
            'title'           => 'タイトル',
            'is_use_code'     => 'クーポン配布方法',

            'service'         => 'サービス内容',
            'prize_code'      => '商品コード',
            'point'           => 'ポイント数',

            'is_count'        => '利用回数制限',
            'count'           => '利用可能な回数',
            'user_type'       => '利用者の種類',


            'is_expiration'   => '有効期限の設定有無',
            'expiration_at'   => '有効期限',

            'is_published'       => '公開日時の設定有無',
            'published_at'    => '公開日時',

        ];
    }


}
