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
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * USERモデル リレーション
         * @return \App\Models\User
        */
        public function user(){
            return $this->belongsTo(User::class);
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


        /**
         * 発送待ち商品[サイドメニュー] $admin->waiting_shippeds
         * @return String
        */
        public function  getWaitingShippedsAttribute()
        {
            $state_id = 11;
            return UserShipped::where('state_id', $state_id)->get();
        }

        /**
         * 未対応お問い合わせ[サイドメニュー] $admin->unresponsed_contacts
         * @return String
        */
        public function  getUnresponsedContactsAttribute()
        {
            return Contact::where('responsed',0)->get();
        }

        /**
         * Fobeesか否か $admin->fobees
         * @return String
        */
        public function getFobeesAttribute()
        {
            $fobees_emails = config('app.fobees_emails') ;//fobeesアカウント

            return in_array($this->email,$fobees_emails);
        }
}
