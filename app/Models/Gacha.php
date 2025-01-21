<?php

namespace App\Models;
use \App\Http\Controllers\GachaPlayCreateUserPrizeMethod as GPCUPMethod;
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
            'nomal'        => 'カスタムボタンあり',
            // 'max_custom'   => 'カスタムボタンあり(上限付き)',
            'no_custom'    => 'カスタムボタンなし',

            // 'one_chance'   => '1回or10回限定',
            'one_time'     => '一回限定',
            'only_oneday'  => '１日１回',
            'only_new_user'=> '新規会員限定',
        ];
    }

    /** カスタムボタンの上限 */
    public static function max_custom_count(){ return 99; }



    /** ガチャの表示可能時間　一覧 */
    public static function times()
    {
        return [
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
         * GachaDiscriptionモデル リレーション discriptions
         * @return \App\Models\GachaDiscription
        */
        public function getDiscriptionsAttribute()
        {
            # ガチャランク配列の取得
            $discriptions_ranks = GachaDiscription::gacha_ranks();

            $array = [];
            foreach ($discriptions_ranks as $gacha_rank_id => $discriptions_rank)
            {
                $gacha_discription = GachaDiscription::where('gacha_id',$this->id)
                ->where('gacha_rank_id',$gacha_rank_id)
                ->first();

                if( $gacha_discription ){ $array[] = $gacha_discription; }
            }

            return $array;
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
            return $this->published_at && $this->published_at <= now()->format('Y-m-d H:i:s') ;
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
         * ガチャ　ログインユーザーのプレイ数 user_played_count
         * @return String
        */
        public function getUserPlayedCountAttribute()
        {
            $user = Auth::user(); //ログインユーザー取得
            return $user
            ? UserGachaHistory::where('gacha_id',$this->id)
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



        /**
         * 天井系ガチャのアド確定までの回転数　add_chance_count
         *
         * @return String $path //表示画像パス
        */
        public function getAddChanceCountAttribute()
        {
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
                    // dd($discription->hit_nums_array);

                    $u_array = [ ...$u_array, ...$filteredArray ];

                }

            }

            # ガチャの口数に応じて当選する当選の、最も近い値
            $array = array_unique($array);// 重複を除く
            sort($array);// 昇順にソート
            $diff = count($array) ?  $array[0] - ($played_count+1): $n+1;
            // dd($array);

            # ユーザーのPLAY数に応じて当選する当選の、最も近い値
            $u_array = array_unique($u_array);// 重複を除く
            sort($u_array);// 昇順にソート
            $u_diff = count($u_array) ? $u_array[0] - ($user_played_count+1) : $n+1;
            // $u_diff =  $n+10;


            # ユーザーのPLAY数に応じて当選する当選の、最も近い値
            $num = $diff<$u_diff ? $diff : $u_diff;


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

            # 演出画像のパスを返す
            if($num==0){
                $image_path = 'site/image/gacha/chance/1.png';
                if( Storage::exists($image_path) ){ return asset('storage/'.$image_path); }
            }
            else if($num>0 && $num<$n){
                $image_path = 'site/image/gacha/chance/10.png';
                if( Storage::exists($image_path) ){ return asset('storage/'.$image_path); }
            }

            return null;
        }



        /**
         * ユーザーランクの商品登録があるか have_user_rank
         * @return String
        */
        public function getHaveUserRankAttribute()
        {
            $gacha_prizes = GachaPrize::where('gacha_id',$this->id)
            ->whereIn('gacha_rank_id', [
                GPCUPMethod::GachaRankIdUserPita(),//ガチャランクID 個人ピタリ賞
                GPCUPMethod::GachaRankIdUserKiri(),//ガチャランクID キリ番
                GPCUPMethod::GachaRankIdUserZoro(),//ガチャランクID ゾロ目
            ])->get();

            return Auth::check() ? $gacha_prizes->count() > 0 : false;
        }
}
