<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/*
| =============================================
|  サイト管理者　ファクトリー
| =============================================
*/
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        # userモデルの生成
        $user = \App\Models\User::factory()->create();

        return [
            'master'   => 1,//管理者編集権限
            'get_mail' => 0,//メール受信設定
            'user_id'  => $user->id,//リレーションID
        ];
    }
}
