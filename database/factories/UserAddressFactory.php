<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/*
| =============================================
|  ユーザーアドレス　ファクトリー
| =============================================
*/
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'postal_code' => $this->faker->prefecture,//'郵便番号'
            'todohuken'   => $this->faker->prefecture,//'住所-都道府県'
            'shikushoson' => $this->faker->prefecture,//'住所-市町村'
            'number'      => $this->faker->prefecture,//'住所-番地'
            'user_id'     => 1,//リレーションID
        ];
    }
}
