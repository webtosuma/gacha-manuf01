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
|  ガチャ　モデル type_text
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
        'updated_prizes_at',// 登録商品更新日時           2025/02/04追加
        'subscription_id',  //サブスクプランID(PointSail) 2025/03/23追加
        'resume',           //説明文 　　　　　　          2025/10/09追加
        'end_published_at', //公開終了日時　　　           2025/10/09追加
        'type_n_count',     // 限定回数(n回限定ガチャ用)   2026/01/27追加
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
        'updated_prizes_at',// 登録商品更新日時 2025/02/04追加
    ];


    /** アクセサーをJSONに含める */
    protected $appends = [
        'is_published',     //公開中かどうか
        'image_path',       //画像ファイルパス
        'type_label',       //ガチャの種類ラベル
        'type_label_admin', //ガチャの種類ラベル

        'remaining_count',     //残りのプレイできる回数
        'sub_auth_user',       //ログインユーザーがサブスクガチャを利用できるか
        'dont_auth_user_rank', //利用できるユーザーランクガチャではない
        'is_disabled_hundredplay_btn', //百連ガチャるボタンのdisabled
        'resume_text',         //ストレージ保存された文章を含む'説明文'
        'user_rank_label',     //ユーザーランク限定ガチャラベル
        'is_type_label_text',  //ガチャの種類等のレベルテキスト表示有無

        'add_chance_image_path', //アド確定予告画像パス
        'add_chance_count',      //天井系ガチャのアド確定までの回転数
        'have_user_rank',        //個人のプレイ数の商品登録
        'user_played_count',     //ログインユーザー個人のプレイ数

        'img_path_one_chance',   //[限定ガチャ画像パス]ワンチャンス限定
        'img_path_one_time',     //[限定ガチャ画像パス]一回限定
        'img_path_only_oneday',  //[限定ガチャ画像パス]1日一回限定
        'img_path_only_new_user',//[限定ガチャ画像パス]新規会委員限定
        'img_path_user_rank',    //[限定ガチャ画像パス]会員ランク限定
        'img_path_card_head',    //[画像パス]ガチャマシーンHead
        'img_path_card_body',    //[画像パス]ガチャマシーンBody

        'remaining_ratio',//[メーター]残数比率
        'remaining_count',//[メーター]残数
        'max_count',      //[メーター]総口数
        'new_label_path', //[メーター]NEW ラベル
        'new_label',        //newか否か
        'img_path_point', //[メーター]ポイントアイコン

        'type_n_played_count',          //[n回限定・1日n回限定]ログインユーザーがガチャを回した数
        'type_n_remaining_count',       //[n回限定・1日n回限定] 残り回数
        'type_n_remaining_count_label', //[n回限定・1日n回限定] 残り回数ラベル

        'route',          //[ルーティング]ガチャ詳細ページ
        'r_prize_history',//[ルーティング]ガチャ商品履歴
        'r_action',       //[ルーティング]ガチャPLAY
        'r_costom',       //[ルーティング]カスタム回数
        'r_admin_show',   //[ルーティング Admin]
        'r_admin_edit',   //[ルーティング Admin]
        'r_admin_copy',   //[ルーティング Admin]
        'r_admin_destroy',//[ルーティング Admin]

        'r_event_show',   //[ルーティング]イベント詳細
        'r_event_play',   //[ルーティング]イベントガチャカで遊ぶ



        'is_popup_btn',          //ポップアップボタン設定　
        'max_custom_type_count', //上限カスタムボタンの上限回数
    ];



    /** ガチャの種類　一覧 */
    public static function types()
    {
        /*.設定は。config.gachaに記述 */
        return config('gacha.types', []);
    }



    /** カスタムボタンの上限 */
    public static function max_custom_count()
    {
        /*.設定は。config.gachaに記述 */
        return config('gacha.max_custom_count', 99);
    }



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
            return $this->belongsTo(GachaCategory::class, 'category_id')
            ->withTrashed();
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


        /**
         * UserGachaHistoryモデル リレーション
         * @return \App\Models\UserGachaHistory
        */
        public function user_gacha_histories()
        {
            return $this->hasMany(UserGachaHistory::class);
        }


        /**
         * PointSailモデル (サブスクプラン)リレーション
         * @return \App\Models\User
        */
        public function subscription(){
            //ガチャ一覧に、サブスク終了時でも表示可能
            return $this->belongsTo(PointSail::class, 'subscription_id')
            ->withTrashed();//削除済みも含む
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

            // return $this->g_prizes->sum('remaining_count');
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

            // $max       = $this->max_count;
            // $remaining = $this->remaining_count;
            // return $max - $remaining;
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


        /**
         * ガチャの種類ラベル type_label
         * @return String
        */
        public function getTypeLabelAttribute()
        {
            # n回限定
            switch ($this->type) {
                case 'n_time':
                return $this->type_n_count.'回限定'; break;

                case 'n_oneday':
                return '1日'.$this->type_n_count.'回限定'; break;
            }

            # 通常・カスタムボタンを含まない
            return !in_array( $this->type, ['nomal','no_custom'] )
            ? $this->types()[$this->type] : null;
        }


        /**
         * ガチャの種類ラベル(Admin用) type_label_admin
         * @return String
        */
        public function getTypeLabelAdminAttribute()
        {
            switch ($this->type) {
                case 'n_time':   return $this->type_n_count.'回限定'; break;

                case 'n_oneday': return '1日'.$this->type_n_count.'回限定'; break;
            }

            return $this->types()[$this->type];//ガチャの種類;
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
            // ->where('created_at', '>', \Carbon\Carbon::parse($this->updated_prizes_at) )//ガチャ商品更新より後の履歴
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
            return new UserRankHistory(['rank_id'=>$this->user_rank_id]);
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

            return Auth::check() && $this->is_published ? $gacha_prizes->count() > 0 : false;
        }



        /**
         * 利用できるユーザーランクガチャではない dont_auth_user_rank
         * @return String
        */
        public function getDontAuthUserRankAttribute()
        {
            $user = Auth::check() ? Auth::user() : null;
            $user_rank_id = $user && $user->now_rank ? $user->now_rank->rank_id : null;
            if( isset($this->user_rank_id) && $this->user_rank_id!=$user_rank_id){ return true; }

            return false;
        }



        /**
         * ユーザーランク限定ガチャラベル user_rank_label
         * @return String
        */
        public function getUserRankLabelAttribute()
        {
            return $this->user_rank && $this->user_rank->label
            ? $this->user_rank->label.'会員' : null;
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
            if( in_array( $this->type, ['n_time', 'n_oneday']) ){ $max = $this->type_n_remaining_count; }

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


        /**
         * ガチャの種類等のレベルテキスト表示有無 is_type_label_text
         * @return Bool
        */
        public function getIsTypeLabelTextAttribute() : Bool
        {
            $text_model = new Text();
            return $text_model->gacha_settings_type_label_text;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー(画像パス)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Newラベル(新規公開のみ) new_label_path
         * @return String
        */
        public function getNewLabelPathAttribute()
        {
            $image_path = 'storage/site/image/new_icon/index.png';

            $published_at = $this->published_at ? $this->published_at->toDateTimeString() : '';
            $new_start_at = now()->subday(7)->toDateTimeString();//減算
            $bool = $new_start_at < $published_at;

            $text_model = new Text();

            return $bool
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( $image_path ) : '';
        }


        /**
         * 画像パス　ポイントアイコン img_path_point
         * @return String
        */
        public function getImgPathPointAttribute()
        {
            return asset( 'storage/site/image/point_icon/index.png' );
        }


        /**
         * 画像パス　1回or10回限定 img_path_one_chance
         * @return String
        */
        public function getImgPathOneChanceAttribute()
        {
            $text_model = new Text();

            return $this->type=='one_chance'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/one_chance.png' ) : null;
        }

        /**
         * 画像パス　一回限定 img_path_one_time
         * @return String
        */
        public function getImgPathOneTimeAttribute()
        {
            $text_model = new Text();

            return $this->type=='one_time'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/one_time.png' ) : null;
        }

        /**
         * 画像パス　1日一回限定 img_path_only_oneday
         * @return String
        */
        public function getImgPathOnlyOnedayAttribute()
        {
            $text_model = new Text();

            return $this->type=='only_oneday'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/only_oneday.png' ) : null;
        }

        /**
         * 画像パス　新規会委員限定 img_path_only_new_user
         * @return String
        */
        public function getImgPathOnlyNewUserAttribute()
        {
            $text_model = new Text();

            return $this->type=='only_new_user'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/only_new_user.png' ) : null;
        }

        /**
         * 会員ランク限定 img_path_user_rank
         * @return String
        */
        public function getImgPathUserRankAttribute()
        {
            $text_model = new Text();

            return $this->user_rank_id!=''
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? $this->user_rank->image_path : null;
        }


        /**
         * スライド画像　slide_imgs
         * @return String
        */
        public function getSlideImgsAttribute()
        {
            # スライド表示用商品の取得
            $g_prizes = GachaPrize::where('gacha_id',$this->id)
            ->where('gacha_rank_id',1001)
            ->get();

            # 登録商品がなければ,nullを返す
            if( $g_prizes->count()==0 ){ return null; }

            # 商品の画像パス配列を返す
            $array = [];
            foreach ($g_prizes as $g_prize) { $array[] = $g_prize->prize->image_path; }
            return $array;
        }



        /**
         * [画像パス]ガチャマシーンHead img_path_card_head
         * @return String
        */
        public function getImgPathCardHeadAttribute()
        {

            $text_model = new Text();

            return $text_model->gacha_settings_card_image
            ? $text_model->gacha_settings_card_image_head : null;
        }



        /**
         * [画像パス]ガチャマシーンBody img_path_card_body
         * @return String
        */
        public function getImgPathCardBodyAttribute()
        {

            $text_model = new Text();

            return $text_model->gacha_settings_card_image
            ? $text_model->gacha_settings_card_image_body : null;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー(n回限定・1日n回限定)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [n回限定・1日n回限定] ログインユーザーがガチャを回した数 type_n_played_count
         * @return Integer
        */
        public function getTypeNPlayedCountAttribute() : Int
        {
            # n回限定ガチャ
            $bool = !in_array( $this->type,[ 'n_time', 'n_oneday' ]);
            if($bool){ return 0; }

            $today   = today();
            $user_id = Auth::check() ? Auth::user()->id : 0 ;


            $query = UserGachaHistory::query();

                $query->where('user_id', $user_id);
                $query->where('gacha_id',$this->id);
                if( $this->type == 'n_oneday' ){
                    $query->whereDate('created_at',$today);
                }

            return $query->sum('play_count');
        }




        /**
         * [n回限定・1日n回限定] 残り回数 type_n_remaining_count
         * @return String
        */
        public function getTypeNRemainingCountAttribute() : Int
        {
            # n回限定ガチャ
            $bool = !in_array( $this->type,[ 'n_time', 'n_oneday' ]);
            if($bool){ return 0; }

            # 最大値（限定回数 or ガチャの残数）
            $max_count = $this->type_n_count<$this->remaining_count ? $this->type_n_count : $this->remaining_count;


            return $max_count > $this->type_n_played_count ? $max_count - $this->type_n_played_count : 0;
        }



        /**
         * [n回限定・1日n回限定] 残り回数ラベル type_n_remaining_count_label
         * @return String
        */
        public function getTypeNRemainingCountLabelAttribute() : ? String
        {
            # n回限定ガチャ
            $bool = !in_array( $this->type,[ 'n_time', 'n_oneday' ]);
            if($bool){ return null; }

            # 最大値（限定回数）
            $max_count = $this->type_n_count;

            $l_head = $this->type=='n_oneday' ? '本日残り ' : '限定残り ';
            $text = $l_head . (string)$this->type_n_remaining_count . '/' . (string)$max_count ;


            return $text;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー(PLAYボタン)
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 1回ガチャるボタンのdisabled is_disabled_oneplay_btn
         * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
         * @return Integer
        */
        public function getIsDisabledOneplayBtnAttribute()
        {
            return $this->isDisabledBtnMethod($this,1);
        }


        /**
         * 10連ガチャるボタンのdisabled is_disabled_tenplay_btn
         * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
         * @return Integer
        */
        public function getIsDisabledTenplayBtnAttribute()
        {
            return $this->isDisabledBtnMethod($this,10);
        }


        /**
         * 百連ガチャるボタンのdisabled is_disabled_hundredplay_btn
         * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
         * @return Integer
        */
        public function getIsDisabledHundredplayBtnAttribute()
        {
            # config設定
            if( ! config('gacha.btn_settings.hundredplay') ){ return -1; }

            # 利用不可(ノーマル,n回限定,1日n回限定 以外)
            if( !in_array( $this->type, ['nomal','n_time', 'n_oneday'] ) ){ return -1; }

            # 非表示
            return $this->isDisabledBtnMethod($this,100);
        }


        /**
         * カスタムボタンのdisabled　is_disabled_custom_btn
         * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
         * @return Integer
        */
        public function getIsDisabledCustomBtnAttribute()
        {
            # config設定
            if( ! config('gacha.btn_settings.custom') ){ return -1; }

            # 終了
            if( in_array( $this->type, ['nomal','max_custom']) && $this->remaining_count == 0 ){ return 1; }

            # n回限定,1日n回限定終了　
            if( in_array( $this->type, ['n_time', 'n_oneday']) && $this->type_n_remaining_count == 0 ){ return 1; }

            # 利用可(ノーマル,最大回数,n回限定,1日n回限定 以外)
            if( in_array( $this->type, ['nomal','max_custom','n_time', 'n_oneday'] ) ){ return 0; }

            # 非表示
            return -1;
        }


        /**
         * ポップアップボタン設定　is_popup_btn
         * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
         * @return Integer
        */
        public function getIsPopupBtnAttribute()
        {
            return config('gacha.btn_settings.popup',false) ? 1 : 0;
        }


            /**
             * プレイボタンのdisabled条件　
             *
             * @param Gacha  $gacha
             * @param Integer $$n //プレイ数
             * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
             * @return Integer
            */
            public function isDisabledBtnMethod( $gacha, $n )
            {
                # 残口数
                $remaining_count = $gacha->remaining_count;

                # ログインユーザーの会員ランク
                // $user = Auth::check() ? Auth::user() : null;
                // $user_rank_id = $user && $user->now_rank ? $user->now_rank->rank_id : null;
                // if( isset($this->user_rank_id) && $this->user_rank_id!=$user_rank_id){ return 1; }

                # ガチャの種類
                switch ($gacha->type)
                {
                    /* 1回or10回 */
                    case 'one_chance':
                        return ($remaining_count >= $n) && !($gacha->played_one_time) ? 0 : 1 ;
                        break;

                    /* 1回限定 */
                    case 'one_time':
                        if( $n!=1 ){ return -1; }//1回ボタン以外非表示
                        return ($remaining_count >= $n) && !($gacha->played_one_time) ? 0 : 1 ;
                        break;

                    /* 一日一回限定 */
                    case 'only_oneday':
                        if( $n!=1 ){ return -1; }//1回ボタン以外非表示
                        if( $remaining_count < $n      ){ return 1; }//終了
                        if( $gacha->played_only_oneday ){ return 2; }//本日は終了
                        return 0;//利用可能
                        break;

                    /* 新規会員限定 */
                    case 'only_new_user':
                        if( $n!=1 ){ return -1; }//1回ボタン以外非表示
                        return ( Auth::check() && !Auth::user()->sevendays_affter_registar )//1週間以内
                        && ($remaining_count >= $n)
                        && !($gacha->played_one_time) //1回のみ
                        ? 0 : 1 ;
                        break;

                    /* n回限定 */
                    case 'n_time':
                        return !($remaining_count < $n) && !($gacha->type_n_remaining_count < $n) ? 0 : 1 ;
                        break;

                    /* 1日n回限定 */
                    case 'n_oneday':
                        if( $remaining_count < $n               ){ return 1; }//終了
                        if( $gacha->type_n_remaining_count < $n ){ return 2; }//本日は終了
                        return 0;//利用可能
                        break;

                    /* */
                }

                /* 広告ボタン */
                if( $gacha->sponsor_ad ){
                    if( $n!=1 ){ return -1; }//1回ボタン以外非表示
                    if( $remaining_count < $n    ){ return 1; }//終了
                    if( $gacha->played_ad_limit ){ return 2; }//本日は終了
                    return 0;//利用可能
                }

                /* 通常 */
                return $remaining_count >= $n ? 0 : 1 ;
            }

        /* */
    /*
    |--------------------------------------------------------------------------
    | ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティング]ガチャ詳細ページ route
         * @return String
        */
        public function getRouteAttribute()
        {
            $params = ['category_code'=>$this->category_code_name, 'gacha'=>$this, 'key'=>$this->key];
            return route('gacha',$params);
        }


        /**
         * [ルーティング]ガチャ商品履歴 r_prize_history
         * 商品履歴の表示許可があるとき&&売り切れのとき
         * @return String
        */
        public function getRPrizeHistoryAttribute()
        {
            $params = ['category_code'=>$this->category->code_name, 'gacha'=>$this, 'key'=>$this->key];

            return config('app.gacha_prize_history') && $this->is_sold_out
            ? route('gacha.prize_history',$params) : null;
        }


        /**
         * [ルーティング]ガチャPLAY r_action
         * @return String
        */
        public function getRActionAttribute()
        {
            $params = ['category_code'=>$this->category->code_name, 'gacha'=>$this, 'key'=>$this->key];

            # アンケートあり
            if(config('app.event_gacha')){
                return route('survey.answering',$params);
            }

            return route('gacha.play',$params);
        }


        /**
         * [ルーティング]カスタム回数 r_costom
         * @return String
        */
        public function getRCostomAttribute()
        {
            $params = ['category_code'=>$this->category->code_name, 'gacha'=>$this, 'key'=>$this->key];
            return route('gacha.custom_count',$params);
        }


        /** [ルーティング Admin]詳細 r_admin_show */
        public function getRAdminShowAttribute(){ return route('admin.gacha.show',$this->id); }

        /** [ルーティング Admin]編集 r_admin_edit */
        public function getRAdminEditAttribute(){ return route('admin.gacha.edit',$this->id); }

        /** [ルーティング Admin]コピー r_admin_copy */
        public function getRAdminCopyAttribute(){ return route('admin.gacha.copy',$this->id); }

        /** [ルーティング Admin]削除 r_admin_destroy */
        public function getRAdminDestroyAttribute(){ return route('admin.gacha.destroy',$this->id); }

    /*
    |--------------------------------------------------------------------------
    | アクセサー(サブスク)
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * ログインユーザーがサブスクガチャを利用できるか sub_auth_user
         * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
         * @return Integer
        */
        public function getSubAuthUserAttribute()
        {

            # サブスクガチャではない
            if( ! $this->subscription ){ return true; }

            # サブスクが非公開
            if( ! $this->subscription->is_published ){ return false; }

            # ユーザーがログアウト中のとき
            if( ! Auth::check() ){ return false; }
            $user = Auth::user();


            # ユーザーの契約中サブスクに該当するか
            $user_sub_ids = $user->user_subscriptions->pluck('subscription_id')->toArray();
            return in_array( $this->subscription_id, $user_sub_ids );
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー(イベント)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ストレージ保存された文章を含む'説明文' resume_text
         * @return String
         */
        public function getResumeTextAttribute()
        {

            $text = $this->resume;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }


        /**
         * [ルーティング]イベント詳細 r_event_show
         * @return String
        */
        public function getREventShowAttribute()
        {
            $params = ['category_code'=>$this->category->code_name, 'gacha'=>$this->id, 'key'=>$this->key];
            return route('event.gacha.show',$params);
        }


        /**
         * [ルーティング]イベントガチャカで遊ぶ r_event_play
         * @return String
        */
        public function getREventPlayAttribute()
        {
            $params = ['category_code'=>$this->category->code_name, 'gacha'=>$this->id, 'key'=>$this->key];
            return route('event.gacha.play',$params);
        }


    /* end */
}
