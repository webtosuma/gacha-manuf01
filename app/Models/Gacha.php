<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ガチャ　モデル
| =============================================
*/
class Gacha extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'category_id',  //リレーション
        'name',  //名前
        'image', //イメージ画像
        'key',   //認証キー
        'type',  //ガチャの種類
        'one_play_point',//1回PLAYポイント数
        'published_at',  //公開設定(利用しない->非公開*消さない)

        'is_meter',//残数メーターの表示有無
        'is_slide',//スライドの表示有無
        'sold_out_at',//売り切れ日時
        'is_sold_out',//売り切れか否か
        'user_rank_id',//会員ランクの指定

        'min_time',// 表示時間下限　2024/04/17追加
        'max_time',// 表示時間上限　2024/04/17追加
        'is_over_date',// 日付を跨ぐか否か（min_time<=max_time:0）2024/04/17追加
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaFactory::new();
    }


    /** Carbonオブジェクトとして利用 */
    protected $dates = [
        'published_at',//公開設定(利用しない->非公開*消さない)
        'sold_out_at', //売り切れ日時
    ];


    /** ガチャの種類　一覧 */
    public static function types()
    {
        return [
            'nomal'        => '通常',
            'no_custom'    => '通常（カスタムボタンなし）',

            'one_time'     => '一回限定',
            'only_oneday'  => '１日１回',
            'only_new_user'=> '新規会員限定',
        ];
    }

    /** ガチャの表示可能時間　一覧 */
    public static function times()
    {
        return [
            // '00:00','01:00','02:00','03:00','04:00','05:00',
            // '06:00','07:00','08:00','09:00','10:00','11:00',
            // '12:00','13:00','14:00','15:00','16:00','17:00',
            // '18:00','19:00','20:00','21:00','22:00','23:00',
            // '24:00',

            '00:00','00:30','01:00','01:30','02:00','02:30','03:00','03:30',
            '04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30',
            '08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30',
            '12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30',
            '16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30',
            '20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30',
            '24:00',

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
         * GachaCategoryモデル リレーション
         * @return \App\Models\GachaCategory
        */
        public function category(){
            return $this->belongsTo(GachaCategory::class, 'category_id');
        }


        /**
         * GachaDiscriptionモデル リレーション
         * @return \App\Models\GachaDiscription
        */
        public function discriptions()
        {
            return $this->hasMany(GachaDiscription::class,'gacha_id');
        }


        /**
         * GachaPrizeモデル リレーション
         * @return \App\Models\GachaPrize
        */
        public function g_prizes()
        {
            return $this->hasMany(GachaPrize::class,'gacha_id')
            ->has('prize')
            ->orderBy('gacha_rank_id','asc'); //ランク順
        }


        /**
         * GachaRankMovieモデル リレーション
         * @return \App\Models\GachaRankMovie
        */
        public function g_rank_movies()
        {
            return $this->hasMany(GachaRankMovie::class,'gacha_id');
        }


        /**
         * SponsorAdモデル リレーション (sponsorAd)
         * @return \App\Models\SponsorAd
        */
        public function sponsor_ad()
        {
            return $this->hasOne(SponsorAd::class,'gacha_id');
        }


        /**
         * SponsorAdモデル リレーション (sponsorAd)
         * @return \App\Models\SponsorAd
        */
        public function sponsor_ads()
        {
            return $this->hasMany(SponsorAd::class,'gacha_id');
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 公開中かどうか is_published
         * @return String
        */
        public function getIsPublishedAttribute()
        {
            return $this->published_at && $this->published_at < now()->format('Y-m-d H:i:s') ;
        }


        /**
         * ガチャ詳細ページ ルーティング route
         * @return String
        */
        public function getRouteAttribute()
        {
            $params = ['category_code'=>$this->category->code_name, 'gacha'=>$this, 'key'=>$this->key];
            return route('gacha',$params);
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
            return $this->g_prizes->sum('remaining_count');
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
            $max       = $this->max_count;
            $remaining = $this->remaining_count;
            return $max - $remaining;
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
            ->where('gacha_id',$this->id)
            ->first();

            return $bool ? true : false ;
        }



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
            ->where('gacha_id',$this->id)
            ->whereDate('created_at', now() )
            ->get()->count();

            // return $count;
            return $count >= $max_count  ;
        }



        /**
         * 1日1回をプレイしたか？ played_only_oneday
         * @return String
        */
        public function getPlayedOnlyOnedayAttribute()
        {
            $user_id = Auth::check() ? Auth::user()->id : 0;

            // 今日の日付を取得
            $today = \Carbon\Carbon::today();

            $bool = UserGachaHistory::where('user_id',$user_id)
            ->where('gacha_id',$this->id)
            ->whereDate('created_at', $today)
            ->first();

            return $bool ? true : false ;
        }



        /**
         * ユーザーランク user_rank
         * @return String
        */
        public function getUserRankAttribute()
        {
            // dd(new UserRankHistory(['rank_id'=>$this->user_rank_id]));
            return new UserRankHistory(['rank_id'=>$this->user_rank_id]);
        }



        /**
         * (新作ガチャ)カウントダウン時間 initial_time
         * @return String
        */
        public function getInitialTimeAttribute()
        {
            if( $this->published_at > now()){
                return now()->diff($this->published_at)->format('%H:%I:%S');
            }
            return null;
        }


        /**
         * (時間帯限定)カウントダウン時間 initial_timezone
         * @return String
        */
        public function getInitialTimezoneAttribute()
        {
            $befor = now()->copy()->subMinutes(30);//表示開始
            $start   = \Carbon\Carbon::parse($this->min_time);//表示終了

            if( $befor <= now()  &&  now() < $start ){
                return now()->diff( $start )->format('%H:%I:%S');
            }
            return null;
        }


        /**
         * (時間帯限定)表示可能か否か is_show_timezone
         * @return String
        */
        public function getIsShowTimezoneAttribute()
        {
            $now_time = now()->format('H:i');//現在時刻

            if( ! $this->is_over_date ){ //日中間の時間帯
                return $this->min_time <= $now_time  &&  $now_time < $this->max_time;

            }else{ //日を跨ぐ時間帯
                return $this->min_time <= $now_time  ||  $now_time < $this->max_time;

            }
            return 'is show';
        }

}
