<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;//オブジェクト化
/*
| =============================================
|  景品発送履歴　テーブル 
| =============================================
*/
class UserShipped extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',             //ユーザー　リレーション
        'user_address_id',     //ユーザーアドレス
        'point_history_id',    //ポイント収支履歴リレーション
        'state_id',            //発送状況
        'shipment_at',         //発送日時
        'shipment_read',       //ユーザーの発送確認
        'arrival_at' ,         //到着日時
        'shipping_company_id', //発送企業ID 2025/11/28追加
        'tracking_code'      , //追跡コード　2025/11/28追加
    ];


    /** Carbonオブジェクトとして利用 */
    protected $dates = [
        'shipment_at',     //発送日時
        'arrival_at' ,     //到着日時
    ];


    /** アクセサーをJSONに含める */
    protected $appends = [
        'code',               //発送コード 0000-0000
        'created_at_format', //申請日フォーマット created_at_format
        'shipment_at_format',//発送日フォーマット
        'r_show',            //[ルーティング]詳細
        'r_admin_show',      //[ルーティング]Admin詳細
        'r_admin_user',      //[ルーティング]Adminユーザー
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserShippedFactory::new();
    }


    /**
     * 景品発送状況
     *
     * @return Array　
     */
    public static function state()
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
            ->withTrashed(); //削除を含む
        }


        /**
         * PointHistoryモデル リレーション
         * @return \App\Models\PointHistory
        */
        public function point_history(){
            return $this->belongsTo(PointHistory::class);
        }

        /**
         * UserAddressモデル リレーション
         * @return \App\Models\PointHistory
        */
        public function user_address(){
            return $this->belongsTo(UserAddress::class)
            ->withTrashed(); //削除を含む
        }

        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function user_prizes()
        {
            return $this->hasMany(UserPrize::class,'shipped_id');
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 発送コード code 0000-0000
         * @return String
        */
        public function getCodeAttribute()
        {
            return sprintf('s%04d-%04d',$this->user_id,$this->id);
        }


        /**
         * 発送日フォーマット created_at_format
         * @return Integer
        */
        public function getCreatedAtFormatAttribute()
        {
            return $this->created_at ? $this->created_at->format('申請日時：Y年m月d日 H:i'): null;
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
    | アクセサー 発送企業
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 発送企業情報の配列 shipping_companies
         * @return String
        */
        public function getShippingCompaniesAttribute()
        {
            return [

                #01 ヤマト運輸
                [ 'id'=>1, 'name'=>'ヤマト運輸', 'url'=>'https://toi.kuronekoyamato.co.jp/cgi-bin/tneko'],

                #02 佐川急便
                [ 'id'=>2, 'name'=>'佐川急便',   'url'=>'https://www.sagawa-exp.co.jp/send/howto-search.html'],

                #03 日本郵便
                [ 'id'=>3, 'name'=>'日本郵便',   'url'=>'https://trackings.post.japanpost.jp/services/srv/search/input'],

            ];
        }


        /**
         * 発送企業情報 shipping_company
         * @return String
        */
        public function getShippingCompanyAttribute()
        {
            foreach ($this->shipping_companies as $shipping_company)
            {
                if (isset($shipping_company['id']) && $shipping_company['id'] == $this->shipping_company_id)
                {
                    // return $shipping_company;   // 見つかった場合、連想配列を返す
                    return new \ArrayObject($shipping_company, \ArrayObject::ARRAY_AS_PROPS);
                }
            }

            return null; // 見つからない場合
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

                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            ## 状態の絞り込み
            switch ($request->state_id)
            {
                /*発送待ち*/
                case '11':
                    $query->where('state_id',11);//
                    $query->orderBy('id');
                    break;

                /*発送済み*/
                case'21':
                    $query->where('state_id',21);
                    $query->orderByDesc('shipment_at');
                    break;
            }

            ## リレーション
            $query->with('user');//ユーザー
            $query->with('user_address');//ユーザーアドレス
            $query->with('user_prizes.prize');
        }



        /**
         * Admin用　EC発送受付数 ->forAdminWaitingCount()
         * @return Integer
        */
        public function scopeForAdminWaitingCount($query)
        {

            ## 状態の絞り込み
            $query->where('state_id',11);//

            return $query->count();
        }


        /**
         * ユーザー用　EC発送済み数 ->forUserSendUnReadCount()
         * @return Integer
        */
        public function scopeForUserSendUnReadCount($query)
        {
            $user = Auth::user();
            if(!$user){ return 0; }
            $query->where('user_id',$user->id);//

            # 状態の絞り込み
            $query->where('state_id',21);//

            # 未読のみ
            $query->where('shipment_read',0);//

            return $query->count();
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
        {
            return route('shipped.show', $this->id);
        }

        /**
         * [ルーティング]Admin詳細 r_admin_show
         * @return Integer
        */
        public function getRAdminShowAttribute()
        { return route('admin.shipped.show', $this->id); }

        /**
         * [ルーティング]Adminユーザー r_admin_user
         * @return Integer
        */
        public function getRAdminUserAttribute()
        { return route('admin.user.show', $this->user->id); }


    /**/

}
