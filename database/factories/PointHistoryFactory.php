<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PointHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'point_sail_id'=>1,  //販売ポイント　リレーション
            'user_id'=>1,        //ユーザー　リレーション
        ];
    }
}
