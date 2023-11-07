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

            //
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
                    'password' => Hash::make('Msb369_pl_sa555'),
                    'name' => '酒井貴弘',
                ],
                'admin' => [
                    'master' => 1,
                ],
            ],
        ];

    }
}
