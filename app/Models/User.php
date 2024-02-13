<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable; //決済


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes; //論理削除の利用
    use Billable; //決済


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
        'twitter_id',//X(旧twitter)ID
        'get_email',
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

        /**
         * UserPrize （高ポイント　トップ3）best_u_prizes //ハンバーガーメニュー
         * @return String
        */
        public function getBestUPrizesAttribute()
        {
            $query = UserPrize::query();

                # 商品情報とのリレーションがあること
                $query->has('prize');

                # ログインユーザーのデータに絞る
                $query->where('user_id',$this->id);

                # ポイント交換ずみのデータを除く
                $query->where('point_history_id',NULL);

                # 発送済みデータを除く
                $query->where('shipped_id',Null);

                $query->orderByDesc('point');
                // $query->orderByDesc('created_at');


            return $query->limit(3)->get();
        }


        /**
         * UserGachaHistoryモデル リレーション ($user->gacha_histories)
         * @return \App\Models\UserGachaHistory
        */
        public function gacha_histories()
        {
            return $this->hasMany(UserGachaHistory::class,'user_id')->orderByDesc('created_at');
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /** 画像なしの時の画像 */
        public static function noImage(){ return asset( 'storage/site/image/user_no_image.png' );}

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  self::noImage();
        }

        /**
         * ポイント残数 $user->point
         * @return String
        */
        public function getPointAttribute() {
            return $this->point_histories->sum('value');
        }



        /**
         * 未読の発送ずみ数 $user->unread_send_shippeds_count
         * @return String
        */
        public function getUnreadSendShippedsCountAttribute()
        {
            return \App\Models\UserShipped::where('user_id',$this->id)
            ->where('shipment_at','<>',NULL)
            ->where('shipment_read',0)
            ->get()->count();
        }




        /**
         * ユーザー保有商品数(Admin) $user->u_prizes_count
         * @return String
        */
        public function getUPrizesCountAttribute()
        {
            $query = UserPrize::query();

                # ログインユーザーのデータに絞る
                $query->where('user_id',$this->id);

                # 商品情報とのリレーションがあること
                $query->has('prize');

                # ポイント交換ずみのデータを除く
                $query->where('point_history_id',NULL);

                # 発送済みデータを除く
                $query->where('shipped_id',Null);

                # 取得が新しい順
                $query->orderByDesc('created_at');

                # 商品テーブル(prize)とのリレーション
                $query->with(['prize.rank' => function ($query) { }]);

            $user_prizes = $query->get();

            return $user_prizes->count();
        }



        /**
         * ガチャを回した回数 $user->gacha_play_count
         * @return String
        */
        public function getGachaPlayCountAttribute()
        {
            return \App\Models\UserGachaHistory::where('user_id',$this->id)
            ->sum('play_count');
        }



        /**
         * 会員登録一週間後 sevendays_affter_registar
         * @return String
        */
        public function getSevendaysAffterRegistarAttribute()
        {
            return $this->created_at->format('Ymd') < now()->subDay(7)->format('Ymd');
        }
}
