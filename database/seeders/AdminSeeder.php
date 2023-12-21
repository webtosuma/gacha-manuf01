<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/*
| =============================================
|  サイト管理者　シーダー
| =============================================
*/
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $datalist = self::dataList();

        foreach ($datalist as $data)
        {
            # userデータの保存
            $user = new \App\Models\User($data['user']);
            $user->save();

            # adminデータの保存
            $admin_data = $data['admin'];
            $admin_data['user_id'] = $user->id;
            $admin = new \App\Models\Admin($admin_data);
            $admin->save();

            # お届け先アドレス
            $user_address = new \App\Models\UserAddress([
                'name' =>'山田　太朗',//宛名
                'tell' =>'00011112222',//電話番号
                'user_id'     => $user->id,    //リレーションID
                'postal_code' => '1234567',//'郵便番号'
                'todohuken'   => '北海道', //'住所-都道府県'
                'shikuchoson' => '函館市',//'住所-市町村'
                'number'      => '1',     //'住所-番地'
                'is_default'  => 1,//デフォルトの送信先か否か
            ]);
            $user_address->save();

            # ポイント付与
            $point_history = new \App\Models\PointHistory([
                'user_id'   => $user->id,          //ユーザー　リレーション
                'value'     => 1000000, //ポイント数
                'reason_id' => 13 //入出理由ID
            ]);
            $point_history->save();

            $point_history = new \App\Models\PointHistory([
                'user_id'   => $user->id,          //ユーザー　リレーション
                'value'     => 10000, //ポイント数
                'price'     => 10000,
                'reason_id' => 11 //入出理由ID
            ]);
            $point_history->save();

            $point_history = new \App\Models\PointHistory([
                'user_id'   => $user->id,          //ユーザー　リレーション
                'value'     => 50000, //ポイント数
                'price'     => 50000,
                'reason_id' => 11 //入出理由ID
            ]);
            $point_history->save();
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
                'user' => [
                    'email' => 't.sakai@tosuma.ltd',
                    'password' => Hash::make('password'),
                    'name' => '酒井　貴弘',
                ],
                'admin' => [
                    'master' => 1,
                ],
            ],
            [
                'user' => [
                    'email' => 'n.akutagawa@tosuma.ltd',
                    'password' => Hash::make('password'),
                    'name' => '芥川　伸雄',
                ],
                'admin' => [
                    'master' => 0,
                    'get_mail'=>0,
                ],
            ],
            [
                'user' => [
                    'email' => 'na@tosuma.ltd',
                    'password' => Hash::make('password'),
                    'name' => 'TOSUMA',
                ],
                'admin' => [
                    'master' => 1,
                    'get_mail'=>0,
                ],
            ],
            [
                'user' => [
                    'email' => 'na@fobees.ltd',
                    'password' => Hash::make('password'),
                    'name' => 'Fobees',
                ],
                'admin' => [
                    'master' => 0,
                    'get_mail'=>0,
                ],
            ],

        ];

    }
}
