<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  スポンサー モデル
| =============================================
*/
class Sponsor extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'user_id'
    ];




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
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 住所 $sponsor->address
         * @return String
        */
        public function getAddressAttribute()
        {
            return $this->user->addresses[0];
        }

        /**
         * 名前 $sponsor->name
         * @return String
        */
        public function getNameAttribute()
        {
            return $this->user->name;
        }

        /**
         * メールアドレス $sponsor->email
         * @return String
        */
        public function getEmailAttribute()
        {
            return $this->user->email;
        }

        /**
         * 電話番号 $sponsor->tell
         * @return String
        */
        public function getTellAttribute()
        {
            return $this->address->tell;
        }

}
