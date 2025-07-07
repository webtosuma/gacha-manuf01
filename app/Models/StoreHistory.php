<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
/*
| =============================================
|  EC 注文履歴　モデル
| =============================================
*/
class StoreHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code'            ,//履歴コード
        'user_id'         ,//ユーザーリレーション
        'user_address_id' ,//発送先アドレス(保存用)

        'use_point'       ,//利用ポイント(保存用)
        'redemption_point',//還元ポイント(保存用)
        'shipped_price'   ,//発送料金

        /* 決済完了後登録 */
        'done_at'                    ,//決済完了日時
        'stripe_checkout_session_id' ,//Stripe決済完了ID
        'use_point_history_id'       ,//利用ポイント履歴ID
        'redemption_point_history_id',//還元ポイント履歴ID

        'state_id'     ,//発送状況
        'shipment_at'  ,//発送日時
        'shipment_read',//ユーザーの発送確認
        'arrival_at'   ,//到着日時
    ];


    /** アクセサーをJSONに含める */
    protected $appends = [
        'done_at_format',    //購入日フォーマット
        'shipment_at_format',//発送日フォーマット
        'r_show',            //[ルーティング]詳細
        'r_admin_show',      //[ルーティング]Admin詳細
        'r_admin_user',      //[ルーティング]Adminユーザー
    ];


    /** 型指定 */
    protected $casts = [
        'done_at'     => 'datetime',//決済完了日時
        'shipment_at' => 'datetime',//発送日時
        'arrival_at'  => 'datetime',//到着日時
    ];



    /* 重複しないコードの生成 */
    public function CreateCode()
    {
        $code = ''; $n =8;
        while ( !$code ) {
            $str = 'sh_'.Str::random($n);
            $model = self::where('code', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }


    /**
     * 発送状況
     *
     * @return Array　
     */
    public static function states()
    {
        return [
            //発送前
            11 => '発送待ち',

            //発送後
            21 => '発送済み',

            //ユーザー
            // 31 => '受取済み',
        ];
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
            return $this->belongsTo(User::class)
            ->withTrashed();//削除済みも含む
        }

        /**
         * UserAddressモデル リレーション
         * @return \App\Models\UserAddress
        */
        public function address()
        {
            return $this->belongsTo(UserAddress::class,'user_address_id')
            ->withTrashed();//削除済みも含む
        }

        /**
         * StoreKeepモデル リレーション
         * @return \App\Models\StoreKeep
        */
        public function store_keeps(){
            return $this->hasMany(StoreKeep::class,'store_history_id');
        }

        /**
         * PointHistoryモデル リレーション(利用ポイント履歴)
         * @return \App\Models\PointHistory
        */
        public function use_point_history(){
            return $this->belongsTo(PointHistory::class,'use_point_history_id')
            ->withTrashed();//削除済みも含む
        }

        /**
         * PointHistoryモデル リレーション(還元ポイント履歴ID)
         * @return \App\Models\PointHistory
        */
        public function redemption_point_history(){
            return $this->belongsTo(PointHistory::class,'redemption_point_history_id')
            ->withTrashed();//削除済みも含む
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /** checkoutの決済名 product_name */
        public function getProductNameAttribute(){
            $first_item_name = $this->store_keeps[0]->store_item->name;
            $sum_items_count = $this->sumItemsCount();
            $multiple_items  = $this->store_keeps->count()>1 ? '...他、' : '';

            return "『{$first_item_name}』{$multiple_items}合計{$sum_items_count}点";
        }



        /**
         * 購入日フォーマット done_at_format
         * @return Integer
        */
        public function getDoneAtFormatAttribute()
        {
            return $this->done_at ? $this->done_at->format('ご購入日時：Y年m月d日 H:i'): null;
        }

        /**
         * 発送日フォーマット shipment_at_format
         * @return Integer
        */
        public function getShipmentAtFormatAttribute()
        {
            return $this->shipment_at ? $this->shipment_at->format('発送日：Y年m月d日 H:i'): null;
        }

    /*
    |--------------------------------------------------------------------------
    | メソッド 合計値算出
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 購入するカート商品ID配列　store_keeps_ids
         * @return Boolean
        */
        public function getStoreKeepsIdsAttribute()
        {
            return $this->store_keeps->pluck('id')->toArray();
        }




        /**
         * 購入するカート商品の合計点数　sumItemsCount()
         * @param  Array $store_keeps_ids
         * @return Boolean
        */
        public function sumItemsCount( $store_keeps_ids=null )
        {
            $store_keeps = !$store_keeps_ids
            ? $this->store_keeps : StoreKeep::find($store_keeps_ids);

            return $store_keeps->sum('count');
        }



        /**
         * 購入するカート商品の還元ポイント　sumItemsPointsRedemption()
         * @param  Array $store_keeps_ids
         * @return Boolean
        */
        public function sumItemsPointsRedemption( $store_keeps_ids=null )
        {
            $point = 0;

            # 購入するカート商品情報
            $store_keeps = !$store_keeps_ids
            ? $this->store_keeps : StoreKeep::find($store_keeps_ids);


            # 還元ポイントの計算
            foreach ($store_keeps as $store_keep) {
                $point += $store_keep->sum_points_redemption;
            }
            return $point;
        }



        /**
         * 購入するカート商品の[小計]　sumItemsPrice()
         * @param  Array $store_keeps_ids
         * @return Boolean
        */
        public function sumItemsPrice( $store_keeps_ids=null )
        {
            $price = 0;

            # 購入するカート商品情報
            $store_keeps = !$store_keeps_ids
            ? $this->store_keeps : StoreKeep::find($store_keeps_ids);


            # 還元ポイントの計算
            foreach ($store_keeps as $store_keep) {
                $price += $store_keep->sum_price;
            }
            return $price;
        }



        /**
         * 購入するカート商品の[請求金額]　totalItemsPrice()
         * @param  Array $store_keeps_ids
         * @return Boolean
        */
        public function totalItemsPrice( $store_keeps_ids=null )
        {
            # 購入するカート商品の[小計]
            $sum_item_price = $this->sumItemsPrice($store_keeps_ids);

            # 発送料金
            $shipped_price  = $this->shipped_price ?? 0;

            # 利用ポイント
            $use_point      = $this->use_point ?? 0;

            return  ($sum_item_price + $shipped_price - $use_point);
        }


    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ユーザー・Admin共用スコープ ->forUserAdmin($request)
        */
        public function scopeForUserAdmin($query,$request=null)
        {
            ## 月の絞り込み
            if($request->month)
            {
                $startDate = \Carbon\Carbon::parse($request->month)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();

                $query->whereBetween('done_at', [$startDate, $endDate]);
            }

            ## 状態の絞り込み
            switch ($request->state_id)
            {
                /*発送待ち*/
                case '11':
                    $query->where('state_id',11);//
                    $query->orderByDesc('done_at');
                    break;

                /*発送済み*/
                case'21':
                    $query->where('state_id',21);
                    $query->orderByDesc('shipment_at');
                    break;
            }
            $query->orderByDesc('created_at');

            ## リレーション
            $query->with('user');//ユーザー
            $query->with('address');//ユーザーアドレス
            $query->with('store_keeps.store_item');
        }


    /*
    |--------------------------------------------------------------------------
    | ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティング]詳細 r_show
         * @return Integer
        */
        public function getRShowAttribute()
        { return route('store.shipped.show', $this->code); }

        /**
         * [ルーティング]Admin詳細 r_admin_show
         * @return Integer
        */
        public function getRAdminShowAttribute()
        { return route('admin.store.shipped.show', $this->id); }

        /**
         * [ルーティング]Adminユーザー r_admin_user
         * @return Integer
        */
        public function getRAdminUserAttribute()
        { return route('admin.user.show', $this->user->id); }



    /**/
}
