<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  販売用ポイント　モデル
| =============================================
*/
class PointSail extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'value',           //実際付与されるポイント
        'price',           //管理者編集権限
        'service',         //サービス差異
        'is_published',    //公開設定(利用しない->非公開*消さない)
        'stripe_id',       //Stipeの商品ID

        'is_subscription'   ,//サブスクリプションか否か
        'sub_image'         ,//サブスク用-画像    (2025/03/2追加)
        'sub_description'   ,//サブスク用-説明文  (2025/03/2追加)
        'sub_label'         ,//セブスク用-見出し  (2025/03/2追加)
        'sub_billing_cycle' ,//セブスク用-請求期間 (2025/03/2追加)

    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\PointSailFactory::new();
    }



    /** サブスク請求サイクル */
    public static function SubscriptionBillingCycles(){ return[
        '月額','週額','日額','3ヶ月','6ヶ月','12ヶ月'
    ]; }




    /* 重複しないコード('stripe_id')の生成 */
    public static function CreateCode()
    {
        $code = ''; $n =12;
        while ( !$code ) {
            $str = Str::random($n);
            $model = self::where('stripe_id', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * UserSubscriptionモデル リレーション $subscription->user_subscriptions //契約中
         * @return \App\Models\UserSubscription
        */
        public function user_subscriptions(){
            return $this->hasMany(UserSubscription::class,'subscription_id')
            ->orderByDesc('created_at')
            ->with('user');
        }

        /**
         * Gachaモデル リレーション $subscription->sub_gachas //サブスクガチャ
         * @return \App\Models\Gacha
        */
        public function sub_gachas(){
            return $this->hasMany(Gacha::class,'subscription_id')
            ->orderByDesc('created_at');
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 付与チケット ticket
         * @return Integer
        */
        public function getTicketAttribute()
        {
            $ticket = floor( $this->price / 1000 );

            return env('NEW_TICKET_SISTEM',false) && !env('NEW_TICKET_SISTEM_NOTICKET',false) ? $ticket: 0 ;
        }


        /**
         * 画像ファイルパス (サブスク用-画像) sub_image_path
         * @return String
        */
        public function getSubImagePathAttribute()
        {
            return $this->sub_image && Storage::exists($this->sub_image) ?
            asset( 'storage/'.$this->sub_image ) :  null;
        }

        /**
         * ストレージ保存された文章（サブスク用-説明文） $infomation->sub_description_text
         * @return String
         */
        public function getSubDescriptionTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->sub_description;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー サブスクリプション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * サブスク・ポイント履歴用 入出理由ID (sub_reason_ids)
         * @return String
         */
        public function getSubReasonIdsAttribute()
        {
            if( !$this->is_subscription ){ return  []; }

            return [
                'create' => 2000 + ($this->id*10) + 1, //2XX1
                'update' => 2000 + ($this->id*10) + 2, //2XX1
                'delete' => 2000 + ($this->id*10) + 3, //2XX1
            ];
        }


        /**
        * サブスク・ポイント履歴用 入出理由ラベル (sub_reason_labels)
        * @return String
        */
        public function getSubReasonlabelsAttribute()
        {
            $labels = [
                'create' => 'サブスク『'.$this->sub_label.'』新規契約',
                'update' => 'サブスク『'.$this->sub_label.'』契約更新',
                'delete' => 'サブスク『'.$this->sub_label.'』解約',
            ];

            $array = [];
            foreach  ( $this->sub_reason_ids as $key => $reason_id ) {
                $array[$reason_id] = $labels[$key];
            }
            return $array;
        }



        /**
        * サブスク・ログインユーザーが契約しているか (auth_user_sub)
        * @return String
        */
        public function getAuthUserSubAttribute()
        {
            if( ! Auth::check() ){ return null; }

            $user = Auth::user();

            return UserSubscription::where('subscription_id',$this->id)
            ->where('user_id',$user->id)->first();
        }



}
