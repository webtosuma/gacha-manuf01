<?php

namespace App\Models;
// use \App\Http\Controllers\GachaPlayCreateUserPrizeMethod as GPCUPMethod;
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
        'end_published_at',//公開設定(利用しない->非公開*消さない)
        'sold_out_at', //売り切れ日時
        'updated_prizes_at',// 登録商品更新日時 2025/02/04追加
    ];


    /** アクセサーをJSONに含める */
    protected $appends = [
        'is_published',      //公開中かどうか
        'published_status', //公開判定
        'image_path',        //画像ファイルパス
        'type_label',        //ガチャの種類ラベル
        'type_label_admin',  //ガチャの種類ラベル

        'remaining_count',     //残りのプレイできる回数
        'sub_auth_user',       //ログインユーザーがサブスクガチャを利用できるか
        'dont_auth_user_rank', //利用できるユーザーランクガチャではない
        'is_disabled_hundredplay_btn', //百連ガチャるボタンのdisabled
        'resume_text',         //ストレージ保存された文章を含む'説明文'
        'user_rank_label',     //ユーザーランク限定ガチャラベル
        'is_type_label_text',  //ガチャの種類等のレベルテキスト表示有無
        'btn_styles', // ガチャるボタンのCSSクラス
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

        use \App\Models\Gacha\Relations;



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

        use \App\Models\Gacha\Accessors;



    /*
    |--------------------------------------------------------------------------
    | ガチャの種類
    |--------------------------------------------------------------------------
    |
    |
    */

        use \App\Models\Gacha\Types;



    /*
    |--------------------------------------------------------------------------
    | 会員ランク
    |--------------------------------------------------------------------------
    |
    |
    */

        use \App\Models\Gacha\UserRanks;



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
            # config設定
            if( ! config('gacha.btn_settings.oneplay',true) ){ return -1; }

            return $this->isDisabledBtnMethod($this,1);
        }


        /**
         * 10連ガチャるボタンのdisabled is_disabled_tenplay_btn
         * (-1:非表示, 0:利用可, 1:終了, 2:本日は終了, )
         * @return Integer
        */
        public function getIsDisabledTenplayBtnAttribute()
        {
            # config設定
            if( ! config('gacha.btn_settings.tenplay',true) ){ return -1; }

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

                    /* n回限定(カスタムボタンなし) */
                    case 'n_time_no_custom':
                        return !($remaining_count < $n) && !($gacha->type_n_remaining_count < $n) ? 0 : 1 ;
                        break;

                    /* 1日n回限定(カスタムボタンなし) */
                    case 'n_oneday_no_custom':
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



        /**
         * ガチャるボタンのCSSクラス btn_styles
         * @return Array
        */
        public function getBtnStylesAttribute()
        {
            # ヒアドキュメントを一列の文字列に変換
            $styles = [];
            $texts = config('gacha.btn_styles');
            foreach ( $texts as $key => $text) {
                $styles[$key] = str_replace(["\r\n", "\r", "\n"], ' ', $text);
            }
            return $styles;
        }

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
