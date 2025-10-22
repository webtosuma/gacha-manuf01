<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * 認可設定
     */
    public function authorize()
    {
        return true;
    }



    /**
     * バリデーションルール
     */
    public function rules()
    {
        $rules = [
            'name'        => ['required', 'string', 'max:140'],
            'email'       => ['required', 'email', 'unique:users'],
            'twitter_id'  => ['regex:/^@.+/', 'string', 'max:140', 'nullable'],
            'birthday_y'  => ['nullable', 'integer'],
            'birthday_m'  => ['nullable', 'integer'],
            'birthday_d'  => ['nullable', 'integer'],
        ];

        // 現在ログイン中のユーザー
        $user = Auth::user();
        $request = $this->all();

        // 前回メールアドレスと同じなら unique除外
        if (isset($request['email']) && $user->email === $request['email']) {
            $rules['email'] = ['required', 'email'];
        }

        return $rules;
    }



    /**
     * バリデーション前に birthday の組み合わせを検証
     */
    protected function prepareForValidation()
    {
        $birthday = isset($this->birthday_y, $this->birthday_m, $this->birthday_d)
            ? sprintf('%04d-%02d-%02d', $this->birthday_y, $this->birthday_m, $this->birthday_d)
            : null;

        $this->merge(['birthday' => $birthday]);
    }



    /**
     * バリデーション後の追加チェック
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $birthday = $this->birthday;

            # null の場合
            if ( config('app.min_age') && is_null($birthday) )
            {
                $validator->errors()->add('birthday', '誕生日を正しく入力してください。');
                return;
            }

            # 有効な日付でなければエラー
            if ( config('app.min_age') && !strtotime($birthday) )
            {
                $validator->errors()->add('birthday', '誕生日の日付が正しくありません。');
            }

            
        });
    }



    /**
     * 日本語属性名
     */
    public function attributes()
    {
        return [
            'name'       => 'アカウント名',
            'email'      => 'メールアドレス',
            'twitter_id' => 'X(旧twitter)ID',
            'birthday'   => '誕生日',
        ];
    }


}
