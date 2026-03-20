<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/*
| =============================================
|  Manufacturer用　ガチャタイトル ファクトリー
| =============================================
*/
class ManufGachaTitleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gacha_category_id' => 1, // 固定

            'name' => $this->faker->words(3, true),

            'image_samune' => $this->faker->imageUrl(640, 480, 'products', true),

            'description' => $this->faker->sentence(20),

            'price' => $this->faker->numberBetween(100, 1000),

            'estimated_shipping_at' => $this->faker->optional()->dateTimeBetween('+1 week', '+1 month'),
            'sales_start_at' => now(),
            'sales_end_at' => $this->faker->dateTimeBetween('+1 week', '+2 months'),

            'published_start_at' => now(),
            'published_end_at' => $this->faker->dateTimeBetween('+1 week', '+2 months'),

            'set_contents' => $this->faker->optional()->sentence(),
            'prize_size' => $this->faker->optional()->randomElement(['S', 'M', 'L']),
            'prize_materials' => $this->faker->optional()->word(),
            'age_range' => $this->faker->optional()->randomElement(['3+', '7+', '12+']),
            'copy_right' => $this->faker->optional()->company(),
        ];
    }
}
