<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\CouponHistory;
use App\Models\User;
use App\Models\PointHistory;
/*
| =============================================
|  クーポン　シーダー
| =============================================
*/
class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # クーポン登録
        $datalist = self::dataList();

        foreach ($datalist as $data)
        {
            $coupon = new Coupon($data);
            $coupon->save();
        }


        # クーポン履歴
        $user = User::first();
        $point_history = new PointHistory([
            'user_id'   => $user ->id,//ユーザーリレーション
            'value'     => $coupon->point,//ポイント数
            'reason_id' => 17,//入出理由ID
        ]);
        $point_history->save();
        $coupon_history = new CouponHistory([
            'user_id'         => $user ->id,//ユーザーリレーション
            'coupon_id'       => $coupon->id,//クーポンリレーション
            'point_history_id'=> $point_history->id,//ポイント履歴(ポイント交換の時)
            // 'user_prize_id'   =>'',//ガチャ商品ID(商品交換の時)
        ]);
        $coupon_history->save();
    }




    /**
     * サイト管理者情報
     *
     * @return Array
     */
    public function dataList()
    {
        return   [
            //テスト：ポイント付与
            [
                'title'           => 'テスト：ポイント付与',//タイトル
                'code'            => Coupon::CreateCode(),//コード
                // 'prize_id'        => '',//ガチャ商品ID
                'point'           => '100',//付与ポイント
                'count'           => '1',//利用可能な回数
                'user_type'       => 'all_user',//利用者の種類
                // 'target_user_ids' => '',//対象ユーザーのID
                // 'is_done'         => '',//終了か否か
                'published_at'    => now(),//公開日時
                'expiration_at'   => now()->addMonth(),//有効期限
            ],
            //テスト：商品付与
            [
                'title'           => 'テスト：商品付与',//タイトル
                'code'            => Coupon::CreateCode(),//コード
                'prize_id'        => 1,//ガチャ商品ID
                // 'point'           => '100',//付与ポイント
                'count'           => '1',//利用可能な回数
                'user_type'       => 'all_user',//利用者の種類
                // 'target_user_ids' => '',//対象ユーザーのID
                // 'is_done'         => '',//終了か否か
                'published_at'    => now(),//公開日時
                'expiration_at'   => now()->addMonth(),//有効期限
            ],
            //テスト：ポイント付与・コード入力
            [
                'title'           => 'テスト：ポイント付与',//タイトル
                'code'            => Coupon::CreateCode(),//コード
                // 'prize_id'        => '',//ガチャ商品ID
                'point'           => '100',//付与ポイント
                'count'           => '1',//利用可能な回数
                'user_type'       => 'all_user',//利用者の種類
                // 'target_user_ids' => '',//対象ユーザーのID
                // 'is_done'         => '',//終了か否か
                'published_at'    => now(),//公開日時
                'expiration_at'   => now()->addMonth(),//有効期限
                'is_use_code'=>1,
            ],
            //テスト：終了
            [
                'title'           => 'テスト：ポイント付与',//タイトル
                'code'            => Coupon::CreateCode(),//コード
                // 'prize_id'        => '',//ガチャ商品ID
                'point'           => '100',//付与ポイント
                'count'           => '1',//利用可能な回数
                'user_type'       => 'all_user',//利用者の種類
                // 'target_user_ids' => '',//対象ユーザーのID
                'is_done'         => 1,//終了か否か
                'published_at'    => now(),//公開日時
                'expiration_at'   => now()->addMonth(),//有効期限
            ],
        ];
    }


}
