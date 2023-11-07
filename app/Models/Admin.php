<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  サイト管理者　モデル
| =============================================
*/
class Admin extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',    //名前
        'master',  //管理者編集権限
        'get_mail',//メール受信設定
        'user_id', //リレーションID
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\AdminFactory::new();
    }


    /*
    |--------------------------------------------------------------------------
    | アクセサー API用
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * API $worker->api
         * @return String
        */
        public function getApiAttribute()
        {
            $array = [
                'user', 'name', 'email',
            ];
            foreach ($array as $key => $value) {
                $this[ $value ] = $this[ $value ];
            }

            return $this;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 名前 $admin->name
         * @return String
        */
        public function getNameAttribute()
        {
            return \App\Models\User::find($this->user_id)->name;
        }

        /**
         * メールアドレス $admin->email
         * @return String
        */
        public function getEmailAttribute()
        {
            return \App\Models\User::find($this->user_id)->email;
        }
}
