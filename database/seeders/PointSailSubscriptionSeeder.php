<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/*
| =============================================
|  販売用 サブスクプラン　シーダー
| =============================================
*/
class PointSailSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datalist = self::dataList();

        foreach ($datalist as $data)
        {
            $pointSail = new \App\Models\PointSail($data);
            $pointSail->save();
        }
    }





    /**
     * サイト管理者情報
     *
     * @return Array
     */
    public function dataList()
    {
        return   [
            [
                'sub_label'   => 'プレミアムプラン',
                'price'       => 30*1000, //支払い金額
                'value'       => 45*1000, //実際付与されるポイント
                'service'     => 0,       //サービス差異
                'stripe_id'   => 'price_1R4df7InLLbCQgY9ySQaDb2h',
                'is_subscription'   =>true,
                'sub_description'   => <<<__
                ● 該当プラン限定ガチャの解禁
                ● 1年継続で12万円相当の商品をプレゼント！
                __,
                'sub_billing_cycle' =>  '月額',
            ],
            [
                'sub_label'   => 'デラックスプラン',
                'price'       => 10*1000, //支払い金額
                'value'       => 15*1000, //実際付与されるポイント
                'service'     => 0,       //サービス差異
                'stripe_id'   => 'price_1R4dgeInLLbCQgY9zoxwjlcQ',
                'is_subscription'   =>true,
                'sub_description'   => <<<__
                ● 該当プラン限定ガチャの解禁
                ● 1年継続で5万円相当の商品をプレゼント！
                __,
                'sub_billing_cycle' =>  '月額',
            ],
            [
                'sub_label'   => 'レギュラープラン',
                'price'       => 5*1000, //支払い金額
                'value'       => 6*1000, //実際付与されるポイント
                'service'     => 0,       //サービス差異
                'stripe_id'   => 'price_1R4dhXInLLbCQgY9v32FmWQy',
                'is_subscription'   =>true,
                'sub_description'   => <<<__
                ● 該当プラン限定ガチャの解禁
                __,
                'sub_billing_cycle' =>  '月額',
            ],
            [
                'sub_label'   => '毎日テストプラン',
                'price'       => 50, //支払い金額
                'value'       => 1000, //実際付与されるポイント
                'service'     => 0,       //サービス差異
                'stripe_id'   => 'price_1R4dhXInLLbCQgY9v32FmWQy',
                'is_subscription'   =>true,
                'sub_description'   => <<<__
                * テストプラン
                __,
                'sub_billing_cycle' =>  '日額',

                'is_published' => false,
            ],

        ];

    }
}
