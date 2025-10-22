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
use Illuminate\Support\Carbon;

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
        'image',
        'twitter_id',//X(旧twitter)ID
        'get_email',

        'payjp_customer_id',
        'subscription_id',//サブスクIDカラム　2024/04/18追加
        'fincode_id',     //fincode顧客ID

        'line_id'    ,//[snsログイン]LINE 　  2025/8/26追加
        'facebook_id',//[snsログイン]facebook 2025/8/26追加

        'tfa_key'           ,//二段階認証キー　2025/9/24追加
        'tfa_failures_count',//二段階認証の失敗数　      2025/9/24追加
        'tfa_failures_at'   ,//二段階認証の失敗日時    　2025/9/24追加
        'is_tfa'            ,//二段階認証を利用するか否か 2025/9/24追加

        'birthday', //誕生日.2025/10/22追加
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
        'tfa_failures_at'   => 'datetime',//二段階認証の失敗日時
        'is_tfa'            => 'boolean', //二段階認証を利用するか否か
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



    /** アクセサーをJSONに含める */
    protected $appends = [
        'image_path',     //画像ファイルパス
        'age',            //年齢
        'birthday_format',//誕生日フォーマット
    ];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
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
         * Sponsorモデル リレーション ($user->sponsor) 削除データも含む
         * @return \App\Models\Sponsor
        */
        public function sponsor()
        {
            return $this->hasOne(Sponsor::class,'user_id')->withTrashed();
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

                # ポイントが高い順
                $query->orderByDesc('point');

                # 共通スコープ
                $query->onlyPossessionScope( $this->id );

            return $query->limit(4)->get();
        }


        /**
         * UserGachaHistoryモデル リレーション ($user->gacha_histories)
         * @return \App\Models\UserGachaHistory
        */
        public function gacha_histories()
        {
            return $this->hasMany(UserGachaHistory::class,'user_id')->orderByDesc('created_at');
        }


        /**
         * UserRankHistoryモデル リレーション ($user->user_rank_histories)
         * @return \App\Models\UserRankHistory
        */
        public function user_rank_histories()
        {
            return $this->hasMany(UserRankHistory::class,'user_id');
        }


        /**
         * TicketHistoryモデル リレーション ($user->ticket_histories)
         * @return \App\Models\TicketHistory
        */
        public function ticket_histories()
        {
            return $this->hasMany(TicketHistory::class,'user_id');
        }


        /**
         * UserStoreKeepモデル リレーション ($user->store_keeps)
         * @return \App\Models\UserStoreKeep
        */
        public function store_keeps()
        {
            return $this->hasMany(StoreKeep::class,'user_id')
            ->where('is_buy_now',0)//今すぐ購入の商品を除く
            ->where('done_at',null)//購入済み商品を除く
            ;
        }


        /**
         * 購入済みUserStoreKeepモデル リレーション ($user->done_store_keeps)
         * @return \App\Models\UserStoreKeep
        */
        public function done_store_keeps()
        {
            return $this->hasMany(StoreKeep::class,'user_id')
            ->whereNotNull('done_at')//購入済み商品のみ
            ;
        }


        /**
         * UserStoreKeepモデル リレーション ($user->store_limit_keeps)
         * @return \App\Models\UserStoreKeep
        */
        public function store_limit_keeps()
        {
            return $this->hasMany(StoreKeep::class,'user_id')
            ->where('is_buy_now',0)//今すぐ購入の商品を除く
            ->where('done_at',null)//購入済み商品を除く
            ->limit(4)
            ;
        }

        /**
         * UserStoreKeepモデル リレーション 購入した商品 ($user->store_purchaseds)
         * @return \App\Models\UserStoreKeep
        */
        public function store_purchaseds()
        {
            return $this->hasMany(StoreKeep::class,'user_id')
            ->where('done_at','<>',null)//購入済み商品を除く
            ->orderByDesc('done_at')
            ;
        }

        /**
         * UserStoreKeepモデル リレーション 最近購入した商品(上限あり) ($user->store_limit_purchaseds)
         * @return \App\Models\UserStoreKeep
        */
        public function store_limit_purchaseds()
        {
            return $this->hasMany(StoreKeep::class,'user_id')
            ->where('done_at','<>',null)//購入済み商品を除く
            ->orderByDesc('done_at')
            ->limit(4)
            ;
        }

        /**
         * StoreHistoryモデル リレーション
         * @return \App\Models\StoreHistory
        */
        public function store_histories()
        {
            return $this->hasMany(StoreHistory::class);
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
            // return $this->point_histories->sum('value');//利用禁止！
            return PointHistory::where('user_id',$this->id)->sum('value');
        }


        /**
         * チケット残数 $user->ticket
         * @return String
        */
        public function getTicketAttribute() {
            return $this->ticket_histories->sum('value');
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
         * ユーザー保有商品数 $user->u_prizes_count
         * @return String
        */
        public function getUPrizesCountAttribute()
        {
            $query = UserPrize::query();

                # 共通スコープ
                $query->onlyPossessionScope( $this->id );

            $count = $query->limit(4999)->count();

            return $count < 4999 ? $count :'4,999以上';
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
            return $this->created_at->format('Ymd') < now()->subDay(6)->format('Ymd');
        }



        /**
         * [キャンペーン]紹介元ユーザー recruiter
         * @return String
        */
        public function getRecruiterAttribute()
        {
            $ra = CanpaingIntroductory::where('friend_id',$this->id)->first();
            return $ra ? $ra->recruiter : null;
        }


        /**
         * 最終アクセス時間 last_access_at
         * @return String
        */
        public function getLastAccessAtAttribute()
        {

            $ug_history = PointHistory::where('user_id',$this->id)
            // ->where('reason_id','<>', 16) //商品の取得期限切れによるポイント交換
            // ->where('reason_id','<>', 23) //ポイント期限切れを除く
            ->orderByDesc('created_at')->first();

            return $ug_history ? $ug_history->created_at : $this->created_at;
        }



        /**
         * 今月の会員ランク now_rank
         * ・・・データがないときは、nullを返す
         * @return String
        */
        public function getNowRankAttribute()
        {
            return UserRankHistory::where('user_id',$this->id)
            ->whereYear('created_at',now())
            ->whereMonth('created_at',now())
            ->orderByDesc('created_at')
            ->first();
        }



        /**
         * 直近の会員ランク desc_first_rank
         * ・・・データがないときは、nullを返す
         * @return String
        */
        public function getDescFirstRankAttribute()
        {
            return UserRankHistory::where('user_id',$this->id)
            ->orderByDesc('created_at')
            ->first();
        }


        /**
         * サブスク　subscription
         * ・・・データがないときは、nullを返す
         * @return String
        */
        public function getSubscriptionAttribute()
        {
            $subscriptions = \App\Http\Controllers\StripSubscriptionController::Subscriptions();
            return array_key_exists($this->subscription_id, $subscriptions )
            ? $subscriptions[$this->subscription_id]['label'] : null;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー　ポイント有効期限関係
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 有効期限切れ日 point_deadline_at
         * @return \Carbon\Carbon
        */
        public function getPointDeadlineAtAttribute()
        {
            # 期限・期限なしのとき
            $deadline_date = config('app.user_point_deadline_date');//利用可能期間
            if( ! $deadline_date ){ return null; }

            # ユーザー最終アクセス日
            $last_access_at = $this->last_access_at ;//制度開始以前の所得商品は、制度開始日からカウントスタート
            $last_access_at = \Carbon\Carbon::parse($last_access_at->format('Y/m/d 00:00:00'));

            return $last_access_at->addDay($deadline_date);
        }



        /**
         * 有効期限切れか否か is_point_deadline
         * @return Boorean
        */
        public function getIsPointDeadlineAttribute()
        {
            # 期限・期限なしのとき
            $deadline_date = config('app.user_point_deadline_date');//利用可能期間
            if( ! $deadline_date ){ return null; }

            # ポイントの有効期限日(point_deadline_at)が今より過去か否か
            return $this->point_deadline_at->lt( now() );
        }



        /**
         * 有効期限テキスト point_deadline_text
         * @return Boorean
        */
        public function getPointDeadlineTextAttribute()
        {
            # 期限・期限なしのとき
            $deadline_date = config('app.user_point_deadline_date');//利用可能期間
            if( ! $deadline_date ){ return null; }

            # ポイントが0のとき
            if( ! $this->point ){ return null; }

            # 期限
            // $point_deadline_at = $this->point_deadline_at;
            $point_deadline_at = $this->point_deadline_at->subDay(1);

            return ! $this->is_point_deadline
            ? $point_deadline_at->format('有効期限：Y/m/d 24:00')
            : $point_deadline_at->format('期限切れ：Y/m/d 24:00')
            ;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー サブスク
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * UserSubscriptionモデル リレーション user_subscriptions
         * @return \App\Models\UserSubscription
        */
        public function  user_subscriptions()
        {
            return $this->hasMany(UserSubscription::class,'user_id');
        }


        /**
         * ユーザーの契約中サブスクプラン subscriptions
         * @return Boorean
        */
        public function getSubscriptionsAttribute()
        {
            $array = [];
            foreach ($this->user_subscriptions as $user_subscription) {
                $array[] = $user_subscription->subscription;
            }
            return $array;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー(誕生日)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 年齢 age
         *
         * @return int|null
         */
        public function getAgeAttribute(): ?int
        {
            if ( empty($this->birthday) ) { return null; }

            return Carbon::parse($this->birthday)->age;
        }


        /**
         * 誕生日フォーマット birthday_format
         *
         * @return string|null
         */
        public function getBirthdayFormatAttribute(): ?string
        {
            return $this->birthday ? Carbon::parse($this->birthday)->format('Y-m-d') : '' ;
        }

        /**
         * 誕生日フォーマット(年) birthday_format_y
         *
         * @return string|null
         */
        public function getBirthdayFormatYAttribute(): ?string
        {
            return $this->birthday ? Carbon::parse($this->birthday)->format('Y') : '' ;
        }

        /**
         * 誕生日フォーマット(月) birthday_format_m
         *
         * @return string|null
         */
        public function getBirthdayFormatMAttribute(): ?string
        {
            return $this->birthday ? Carbon::parse($this->birthday)->format('m') : '' ;
        }

        /**
         * 誕生日フォーマット(日) birthday_format_d
         *
         * @return string|null
         */
        public function getBirthdayFormatDAttribute(): ?string
        {
            return $this->birthday ? Carbon::parse($this->birthday)->format('d') : '' ;
        }



        /**
         * 今日が誕生日か判定するアクセサ is_birthday_today
         *
         * @return bool
         */
        public function getIsBirthdayTodayAttribute(): bool
        {
            # 未入力
            if (empty($this->birthday)) { return false; }

            $today    = Carbon::now();
            $birthday = Carbon::parse($this->birthday);

            # 月日が今日と一致するか
            return $today->isSameDay($birthday->setYear($today->year));
        }


    /* ~ */
}
