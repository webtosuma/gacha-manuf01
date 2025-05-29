<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Coupon;
/*
| =============================================
|  クーポン　ファクトリー
| =============================================
*/
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code'            => Coupon::CreateCode(),//コード
            'title'           => 'テストクーポン',//タイトル
            // 'prize_id'        => '',//ガチャ商品ID
            'point'           => '100',//付与ポイント
            'count'           => '1',//利用可能な回数
            'user_type'       => 'all_user',//利用者の種類
            // 'target_user_ids' => '',//対象ユーザーのID
            // 'is_done'         => '',//終了か否か
            'published_at'    => now(),//公開日時
            // 'expiration_at'   => '',//有効期限
        ];
    }
}
