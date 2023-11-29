<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes; //論理削除の利用


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'payjp_customer_id',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }





    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Adminモデル リレーション ($user->admin)
         * @return \App\Models\Admin
        */
        public function admin()
        {
            return $this->hasOne(Admin::class,'user_id');
        }

        /**
         * UserAddressモデル リレーション ($user->address)
         * @return \App\Models\UserAddress
        */
        public function addresses()
        {
            return $this->hasMany(UserAddress::class,'user_id')
            ->orderByDesc('id');
        }


        /**
         * PointHistoryモデル リレーション ($user->point_histories)
         * @return \App\Models\PointHistory
        */
        public function point_histories()
        {
            return $this->hasMany(PointHistory::class,'user_id');
        }


        /**
         * UserPrizeモデル リレーション ($user->u_prizes)
         * @return \App\Models\UserPrize
        */
        public function u_prizes()
        {
            return $this->hasMany(UserPrize::class,'user_id');
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ポイント残数 $user->point
         * @return String
        */
        public function getPointAttribute() {
            return $this->point_histories->sum('value');
        }


}
