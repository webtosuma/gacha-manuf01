<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  会員ランク履歴　モデル
| =============================================
*/
class UserRankHistory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'user_id', //ユーザーID
        'rank_id', //ランクID
        'pt_count',//ポイント消費数
    ];


    /** 最高ランクのID (600:レジェンド) */
    public static function MaxRankId()
    {
        # config.u_rank_ticketにて設定
        $array = config('u_rank_ticket.u_rank_settings.user_ranks',[]);
        return array_key_last( $array );
    }


    /** 会員ランク　一覧 */
    public static function UserRanks()
    {
        # config.u_rank_ticketにて設定
        return config('u_rank_ticket.u_rank_settings.user_ranks',[]);
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
            return $this->belongsTo(User::class)->withTrashed();
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー 会員ランク情報
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 履歴に紐づく、会員ランク情報 this_rank
         * @return Array
        */
        public function getThisRankAttribute()
        {
            $array = $this->UserRanks();
            return array_key_exists( $this->rank_id, $array )
            ? $array[$this->rank_id] : null;
        }


        /** 画像なしの時の画像 */
        public static function noImage(){ return asset( 'storage/site/image/no_image.jpg' );}

        /**　画像ファイルパス image_path*/
        public function getImagePathAttribute()
        {
            $image = $this->this_rank['image'];

            return $image && Storage::exists($image) ?
            asset( 'storage/'.$image ) :  self::noImage();
        }

        /** ラベル label　*/
        public function getLabelAttribute(){ return $this->this_rank ? $this->this_rank['label'] : null;}

        /** コード code　*/
        public function getCodeAttribute(){ return $this->this_rank['code'];}

        /** 昇格に必要なpt消費数 rankup_ptcount　*/
        public function getRankupPtcountAttribute(){ return $this->this_rank['rankup_ptcount'];}

        /** 昇格ボーナス　ポイント point_bonus　*/
        public function getPointBonusAttribute(){ return $this->this_rank['point_bonus'];}

        /** 昇格ボーナス　チケット ticket_bonus　*/
        public function getTicketBonusAttribute(){ return $this->this_rank['ticket_bonus'];}

        /** ポイント購入還元率 point_sail_ratio　*/
        public function getPointSailRatioAttribute(){ return 1+($this->this_rank['point_sail_ratio']/100);}



        /**
         * 次に昇格する会員ランク情報 $user->now_rank->next_rank
         * @return Array
        */
        public function getNextRankAttribute()
        {
            # 会員ランクID配列(0,100,200...)
            $rank_id_array = array_keys( $this->UserRanks() );

            # 今の会員ランクIDの順位
            $id_num = array_search( $this->rank_id, $rank_id_array );

            # ランクアップ後の会員ランクIDの順位(レジェンドの時は、レジェンド)
            $next_num = count($rank_id_array)>$id_num+1 ? $id_num+1 : count($rank_id_array)-1;

            # 次の会員ランクID
            $next_rank_id= $rank_id_array[$next_num];

            # 次の会員ランク情報モデル
            $next_rank_history = new UserRankHistory(['rank_id'=>$next_rank_id]);//次のランクIDを保存


            # 次の会員ランクがなければ、nullを返す
            return $this->rank_id != $this->MaxRankId()
            ? $next_rank_history : null;
        }



        /**
         * 下に降格する会員ランク情報 $user->now_rank->down_rank
         * @return Array
        */
        public function getDownRankAttribute()
        {
            # 会員ランクID配列(0,100,200...)
            $rank_id_array = array_keys( $this->UserRanks() );

            # 今の会員ランクIDの順位
            $id_num = array_search( $this->rank_id, $rank_id_array );
            // $id_num = array_search( 100, $rank_id_array );


            # ランクアップ後の会員ランクIDの順位(ビギナーの時は、ビギナー)
            $down_num = $id_num-1>-1 ? $id_num-1 : 0;

            # 次の会員ランクID
            $down_rank_id= $rank_id_array[$down_num];

            # 次の会員ランク情報モデル
            $down_rank_history = new UserRankHistory(['rank_id'=>$down_rank_id]);//次のランクIDを保存


            # 次の会員ランクがなければ、nullを返す
            return $this->rank_id != 0
            ? $down_rank_history : null;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー ユーザー情報
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ユーザーの今月のpt消費数 total_play_ptcount
         * @return String
        */
        public function getTotalPlayPtcountAttribute()
        {
            $user = $this->user;

            # [ランクアップ基準ID(ポイント履歴)]config.u_rank_ticketにて設定
            $reason_id = (int) config('u_rank_ticket.u_rank_settings.point_history_id', 21 );

            $query = PointHistory::query();

                $query->where('user_id',$user->id)
                ->whereYear( 'created_at',now())
                ->whereMonth('created_at',now())
                ->where('reason_id', $reason_id );//入出理由:ガチャPLAY

            return abs( $query->sum('value') );
        }


        /**
         * 今の会員ランクを維持するために必要なpt消費数 maintain_rank_ptcount
         * @return String
        */
        public function getMaintainRankPtcountAttribute()
        {
            return $this->this_rank['rankup_ptcount'] * 0.50;
        }


        /**
         * 次のランクアップに必要なpt消費数 next_rankup_ptcount
         * @return String
        */
        public function getNextRankupPtcountAttribute()
        {
            return $this->rank_id != $this->MaxRankId()//レジェンドランクでないとき
            ? $this->next_rank->rankup_ptcount
            : $this->maintain_rank_ptcount;//今の会員ランクを維持するために必要なpt消費数
        }


        /**
         * メーター値① meter_warning
         * @return String
        */
        public function getMeterWarningAttribute()
        {
            $value = $this->maintain_rank_ptcount > $this->total_play_ptcount
            ? $this->total_play_ptcount
            : $this->maintain_rank_ptcount;

            return $value / $this->next_rankup_ptcount * 100;
        }


        /**
         * メーター値② meter_success
         * @return String
        */
        public function getMeterSuccessAttribute()
        {
            $value = $this->maintain_rank_ptcount > $this->total_play_ptcount
            ? 0 : $this->total_play_ptcount - $this->maintain_rank_ptcount;

            return $value / $this->next_rankup_ptcount * 100;
        }

}












