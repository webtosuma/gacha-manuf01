<?php
namespace App\Models\Gacha;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\UserGachaHistory;
use App\Models\UserRankHistory;
use App\Models\GachaPrize;
use App\Models\Text;
use \App\Http\Controllers\GachaPlayCreateUserPrizeMethod as GPCUPMethod;

/*
| =============================================
|  ガチャ　モデル [ アクセサー Trait]
| =============================================
*/
trait Accessors
{
    /**
     * 公開中かどうか is_published
     * @return String
    */
    public function getIsPublishedAttribute()
    {
        return $this->published_status===1;
    }



    /**
     * 公開判定 published_status
     *
     * @return Int
     *   1 : 公開中
     *   2 : 公開予約中
     *   0 : 非公開
     */
    public function getPublishedStatusAttribute(): int
    {
        $now = now();

        # 開始・終了　日時
        $start = $this->published_at;
        $end   = $this->end_published_at;


        # start_at,end_at があって、end_atの方が小さい値になってしまっているとき
        if ($start && $end && $start > $end) { return (Int) 0; }

        # 未入力
        if (!$start && !$end) { return (Int) 0; }

        # startが未入力
        if (!$start) { return (Int) 0; }

        # end_at があって、すでに終わっている場合 → 0
        if ($end && $now > $end) { return (Int) 0; }

        # start_at があって、まだ始まっていない場合 → 2
        if ($start && $now < $start) { return (Int) 2; }


        # ここまで来たら有効期間内（または片方nullで条件を満たす）
        return (Int) 1;
    }



    /** 画像なしの時の画像 */
    public static function noImage(){ return asset( 'storage/site/image/no_image.jpg' );}


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
     * 商品・総数 max_count
     * @return String
    */
    public function getMaxCountAttribute()
    {
        return $this->g_prizes->sum('max_count');
    }


    /**
     * 商品・残り数 remaining_count
     * @return String
    */
    public function getRemainingCountAttribute()
    {
        $remaining_count = $this->max_count - $this->played_count;
        return $remaining_count>=0 ? $remaining_count : 0 ;
    }


    /**
     * 商品・残り割合 remaining_ratio
     * @return String
    */
    public function getRemainingRatioAttribute()
    {
        $max       = $this->max_count;
        $remaining = $this->remaining_count;
        return $max>0 ? ( ($remaining/$max) * 100 ) : 0 ;
    }


    /**
     * ガチャ　プレイ数 played_count
     * @return String
    */
    public function getPlayedCountAttribute()
    {
        return $this->user_gacha_histories
        ? $this->user_gacha_histories->sum('play_count') : 0 ;
    }


    /**
     * ガチャ　ログインユーザーのプレイ数 user_played_count
     * @return String
    */
    public function getUserPlayedCountAttribute()
    {
        $user = Auth::user(); //ログインユーザー取得

        return $user
        ? UserGachaHistory::where('gacha_id',$this->id)
        // ->where('created_at', '>', \Carbon\Carbon::parse($this->updated_prizes_at) )//ガチャ商品更新より後の履歴
        ->where('user_id',$user->id)
        ->get()->sum('play_count')
        : 0 ;
    }


    /**
     * ランクの商品ポイント合計 total_point
     * @return String
    */
    public function getTotalPointAttribute()
    {
        $g_prizes = $this->g_prizes;
        $point = 0;
        foreach ($g_prizes as $g_prize) {
            $point +=  $g_prize->prize->point/*ポイント数*/ * $g_prize->max_count/*登録数*/;
        }
        return $point;
    }



    /**
     * 一回プレイしたか？ played_one_time　
     * @return String
    */
    public function getPlayedOneTimeAttribute()
    {
        $user_id = Auth::check() ? Auth::user()->id : 0;

        $bool = UserGachaHistory::where('user_id',$user_id)
        // ->where('created_at', '>', \Carbon\Carbon::parse($this->updated_prizes_at) )//ガチャ商品更新より後の履歴
        ->where('gacha_id',$this->id)
        ->first();

        return $bool ? true : false ;
    }



    /**
     * (新作ガチャ)カウントダウン時間 initial_time
     * @return String
    */
    public function getInitialTimeAttribute()
    {
        $max = now()->copy()->addMinutes( config('app.countdown_minute',30) );
        // $max = now()->copy()->addDays(3);//3日前　新規カウントダウン

        if( $this->published_at>now() && $this->published_at<$max  )
        {
            return now()->copy()->diff($this->published_at)->format('%H:%I:%S');
        }
        return null;
    }



    /**
     * (時間帯限定)カウントダウン時間 initial_timezone
     * @return String
    */
    public function getInitialTimezoneAttribute()
    {
        $now_time = now()->format('H:i');//現在時刻
        $start = \Carbon\Carbon::parse($this->min_time);
        $end   = \Carbon\Carbon::parse($this->max_time);
        if( !($start<=now() && now()<=$end) && $this->is_published )
        {
            $next_start = $now_time < $this->min_time ? $start : $start->addDay();
            return $next_start->diff( now() )->format('%H:%I:%S');
        }
        return null;
    }



    /**
     * (時間帯限定)表示可能か否か is_show_timezone
     * @return String
    */
    public function getIsShowTimezoneAttribute()
    {
        $now_time = now()->copy()->format('H:i');//現在時刻

        if( ! $this->is_over_date ){ //日中間の時間帯
            return $this->min_time <= $now_time  &&  $now_time < $this->max_time;

        }else{ //日を跨ぐ時間帯
            return $this->min_time <= $now_time  ||  $now_time < $this->max_time;

        }
        return 'is show';
    }



    /**
     * カテゴリーコードネーム(カテゴリー削除対応) category_code_name
     * @return String
    */
    public function getCategoryCodeNameAttribute()
    {
        return $this->category ? $this->category->code_name : 'unknown' ;
    }



    /**
     * 上限カスタムボタンの上限回数 max_custom_type_count
     * @return String
    */
    public function getMaxCustomTypeCountAttribute()
    {
        /*.設定は。config.gachaに記述 */
        $max = $this->type=='max_custom' ? config('gacha.max_custom_count', 99) : null ;

        /* n回限定,1日n回限定終了 */
        if( in_array( $this->type, [
            'n_time', 'n_oneday',
            'n_time_no_custom','n_oneday_no_custom',
        ]) ){ $max = $this->type_n_remaining_count; }

        return $max;
    }



    /**
     * newか否か new_label
     * @return String
    */
    public function getNewLabelAttribute()
    {
        $published_at = $this->published_at ? $this->published_at->toDateTimeString() : '';
        $new_start_at = now()->subday(7)->toDateTimeString();//減算
        $bool = $new_start_at < $published_at;

        return $bool ? 'new' : null;
    }



    /*
    |--------------------------------------------------------------------------
    | スポンサー
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * スポンサー広告ガチャを上限までプレイしたか？ played_ad_limit　
         * @return String
        */
        public function getPlayedAdLimitAttribute()
        {
            # スポンサー広告ガチャのみ
            if( !$this->sponsor_ad ){ return false; }

            # 最大値
            $max_count = 10;
            $user_id = Auth::check() ? Auth::user()->id : 0;

            $count = UserGachaHistory::where('user_id',$user_id)
            // ->where('created_at', '>', \Carbon\Carbon::parse($this->updated_prizes_at) )//ガチャ商品更新より後の履歴
            ->where('gacha_id',$this->id)
            ->whereDate('created_at', now() )
            ->get()->count();

            // return $count;
            return $count >= $max_count  ;
        }



    /*
    |--------------------------------------------------------------------------
    | 上限アドガチャ
    |--------------------------------------------------------------------------
    |
    |
    */


        /**
         * 天井系ガチャのアド確定までの回転数　add_chance_count
         *
         * @return String $path //表示画像パス
        */
        public function getAddChanceCountAttribute()
        {
            #アド予告の利用設定確認
            if( !env('ADD_CHANCE_NOTICE',false) ){ return null; }


            # 変数
            $remaining_count  = $this->remaining_count; //残り口数
            $played_count     = $this->played_count;    //済み口数
            $user_played_count = $this->user_played_count;//ユーザー済み口数

            #　『ユーザーのPLAY数に応じて』当選するガチャランクID
            $gacha_u_ranks = [
                GPCUPMethod::GachaRankIdUserPita(),
                GPCUPMethod::GachaRankIdUserKiri(),
                GPCUPMethod::GachaRankIdUserZoro(),
            ];

            # 当選口数配列
            // GPCUPMethod
            $array   = [];//ガチャの口数に応じて当選する当選口数配列
            $u_array = [];//ユーザーのPLAY数に応じて当選する当選口数配列
            $n = 10;


            foreach ($this->discriptions as $discription)
            {
                $gacha_rank_id = $discription->gacha_rank_id;


                # ガチャの口数に応じて当選する当選
                if( !in_array( $gacha_rank_id, $gacha_u_ranks) ){

                    ## 次のplay回数
                    $next_coount = $played_count +1 ;

                    ## 当選の口数で、($next_coount~$next_coount+n)に該当する値のみ抽出
                    $filteredArray = array_filter($discription->hit_nums_array, function ($value) use ($next_coount,$n) {
                        return $value >= $next_coount && $value <= ($next_coount + $n);
                    });

                    $array = [ ...$array, ...$filteredArray ];

                }
                # ユーザーのPLAY数に応じて当選する当選
                else{


                    ## 次のplay回数
                    $next_coount = $user_played_count +1 ;

                    ## 当選の口数で、($next_coount~$next_coount+n)に該当する値のみ抽出
                    $filteredArray = array_filter($discription->hit_nums_array, function ($value) use ($next_coount,$n) {
                        return $value >= $next_coount && $value <= ($next_coount + $n);
                    });


                    $u_array = [ ...$u_array, ...$filteredArray ];

                }

            }


            # ガチャの口数に応じて当選する当選の、最も近い値
            $array = array_unique($array);// 重複を除く
            sort($array);// 昇順にソート
            $diff = count($array) ?  $array[0] - ($played_count+1): null;


            # ユーザーのPLAY数に応じて当選する当選の、最も近い値
            $u_array = array_unique($u_array);// 重複を除く
            sort($u_array);// 昇順にソート
            $u_diff = count($u_array) ? $u_array[0] - ($user_played_count+1) : null;


            # ユーザーのPLAY数に応じて当選する当選の、最も近い値
            if(    $diff  ===null){ $num = $u_diff; }
            elseif($u_diff===null){ $num = $diff;   }
            else{ $num = $diff<$u_diff ? $diff : $u_diff; }



            return $num;
        }



        /**
         * 天井系ガチャのアド確定画像パス　add_chance_image_path
         *
         * @return String $path //表示画像パス
        */
        public function getAddChanceImagePathAttribute()
        {

            # 売り切れのとき
            if($this->is_sold_out){ return null; }
            # 非公開のとき
            // if(!$this->published_at){ return null; }


            $n = 10;
            $num = $this->add_chance_count;

            # 該当なし
            if($num===null){ return null; }

            # 演出画像のパスを返す
            if($num===0){
                $image_path = 'site/image/gacha/chance/1.png';
                if( Storage::exists($image_path) ){ return asset('storage/'.$image_path); }
            }
            else if($num>0 && $num<$n){
                $image_path = 'site/image/gacha/chance/10.png';
                if( Storage::exists($image_path) ){ return asset('storage/'.$image_path); }
            }

            return null;
        }

    /**/

}
