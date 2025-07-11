<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ユーザー取得商品　モデル
| =============================================
*/

class UserPrize extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',              //ユーザー　リレーション
        'prize_id',             //商品リレーション
        'gacha_history_id',     //入手したガチャのID (チケットで取得した時のID：１)
        'point_history_id',     //ポイント交換履歴ID(ポイントと交換て入手した時のみ)
        'shipped_id',           //発送履歴（発送した時のみ）
        'point',                //(商品取得時の)交換ポイント値
        'ticket_history_id',    //チケット->商品交換履歴ID(チッケトで商品入手した時のみ)
        'to_ticket_history_id', //商品->チケット交換(2025/07/11追加)
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserPrizeFactory::new();
    }


    /** アクセサーをJSONに含める */
    protected $appends = [
        'ticket',    //交換チケットの値
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


        /**
         * PointHistoryモデル リレーション
         * @return \App\Models\PointHistory
        */
        public function point_history(){
            return $this->belongsTo(PointHistory::class);
        }


        /**
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class)
            ->withTrashed();
        }


        /**
         * UserGachaHistoryモデル リレーション
         * @return \App\Models\UserGachaHistory
        */
        public function ug_history(){
            return $this->belongsTo(UserGachaHistory::class,'gacha_history_id');
        }

        /**
         * TicketHistoryモデル リレーション(チケット->商品取得)
         * @return \App\Models\TicketHistory
        */
        public function ticket_history(){
            return $this->belongsTo(TicketHistory::class,'ticket_history_id');
        }


        /**
         * TicketHistoryモデル リレーション(商品->チケット交換)
         * @return \App\Models\TicketHistory
        */
        public function to_ticket_history(){
            return $this->belongsTo(TicketHistory::class,'to_ticket_history_id');
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー　
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 交換チケットの値 ticket
         * @return \Carbon\Carbon
        */
        public function getTicketAttribute()
        {
            return isset($this->prize->ticket) ? $this->prize->ticket : 0;
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー　有効期限関係
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 有効期限切れ日 deadline_at
         * @return \Carbon\Carbon
        */
        public function getDeadlineAtAttribute()
        {
            # 制度開始時期
            $start_at = \Carbon\Carbon::parse('2025/01/01');

            #期限なしのとき
            $deadline_date = config('app.user_prize_deadline_date');//利用可能期間
            if( !$deadline_date ){ return null; }

            # 商品取得日
            $created_at = $start_at < $this->created_at ? $this->created_at : $start_at ;//制度開始以前の所得商品は、制度開始日からカウントスタート
            $created_at = \Carbon\Carbon::parse($created_at->format('Y/m/d 00:00:00'));

            return $created_at->addDay($deadline_date);
        }



        /**
         * 有効期限切れか否か is_deadline
         * @return Boorean
        */
        public function getIsDeadlineAttribute()
        {
            #期限なしのとき
            $deadline_date = config('app.user_prize_deadline_date');//利用可能期間
            if( !$deadline_date ){ return false; }

            # 有効期限切れ日(deadline_at)が今より過去か否か
            return $this->deadline_at->lt( now() );
        }



        /**
         * 有効期限テキスト deadline_text
         * @return Boorean
        */
        public function getDeadlineTextAttribute()
        {
            #期限なしのとき
            $deadline_date = config('app.user_prize_deadline_date');//利用可能期間
            if( !$deadline_date ){ return ''; }

            # 期限切れテキスト
            // $text = $this->deadline_at->format('有効期限：Y/m/d H:i');
            $text = $this->deadline_at->subDay(1)->format('有効期限：Y/m/d 24:00');

            return ! $this->is_deadline ? $text : '期限切れ' ;
        }



    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /** 保有中のみ（全体or個人）　onlyPossessionScope($user_id ) */
        public function scopeOnlyPossessionScope( $query ,$user_id )
        {

            # ログインユーザーのデータに絞る
            if($user_id){
                $query->where('user_id',$user_id);
            }

            # 商品情報とのリレーションがあること
            $query->has('prize');

            # ポイント交換ずみのデータを除く
            $query->where('point_history_id',NULL);

            # 商品->チケット交換ずみのデータを除く
            $query->where('to_ticket_history_id',NULL);

            # 発送済みデータを除く
            $query->where('shipped_id',Null);

            # 取得が新しい順
            $query->orderByDesc('id');
            $query->orderByDesc('created_at');

            # 商品テーブル(prize)とのリレーション
            $query->with(['prize.rank' => function ($query) { }]);

            return $query;
        }




}
