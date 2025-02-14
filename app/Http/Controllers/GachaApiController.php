<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use App\Models\PointHistory;
use App\Models\Infomation;
/*
| =============================================
|  ガチャ テスト Apiを利用したガチャ一覧 コントローラー
| =============================================
*/
class GachaApiController extends Controller
{
    /**
     * カテゴリー選択・一覧表示
     *
     * @param \Illuminate\Http\Request $request
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $category_code='all' )
    {
        // $gachas = Gacha::withSum('user_gacha_histories', 'play_count')
        // ->orderByDesc('user_gacha_histories_sum_play_count')
        // ->get();

        // dd($gachas[1]);

        # 表示できないページの処理
        $category = GachaCategory::where('code_name', $category_code)->first();
        if( $category_code!='all' && !$category ){ return \App::abort(404); }


        # 変数

            ## カテゴリー名（ページタイトル）
            $category_name = $category ? $category->name : 'すべて';

            ## 背景画像
            $bg_image = $category ? $category->bg_image_path : GachaCategory::noImage();

            ## ガチャのカテゴリーグループ一覧
            $categories = GachaCategory::where('is_published',1) //公開中
            ->orderBy('created_at')
            ->get();


            ## カードサイズ
            $card_size = $request->card_size ? $request->card_size : null;

            ## 絞り込みキー
            $search_key = $request->search_key ? $request->search_key : null;

            ## 検索キーワード
            $searchs = GachaController::getsearchs();


            ## お知らせ
            $infomations =
            InfomationController::GetInfomationsQuery()
            ->limit(3)
            ->get();

            ## スライド
            $query = self::getPublishedGachas( $category_code, $search_key );
            $gachas = $query->where('is_slide',1)->get();
            $slides = GachaController::getSlides($gachas);

        //

        # viewの表示
        return view('gacha.api_index', compact(
            'category_code', 'category_name', 'bg_image',  'categories', 'card_size',
            'search_key', 'searchs',
            'infomations',
            'slides',
         ) );

    }



    /**
     * API・一覧表示
     *
     * @param \Illuminate\Http\Request $request
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list( Request $request )
    {
        ## カテゴリーコード
        $category_code = $request->category_code ?? 'all';

        ## 絞り込みキー
        $search_key = $request->search_key ? $request->search_key : null;

        ## 表示できるガチャ一覧
        $query = self::getPublishedGachas( $category_code, $search_key );
        $gachas = $query->paginate(3);
        self::addApiGachaData($gachas);//API用の追加データ


        ## カウントダウンガチャ
        $countdown_gachas = GachaController::getCountdownGachas($category_code);


        return response()->json( compact('gachas','countdown_gachas') );

    }




        /**
         * ガチャ一覧で表示できるガチャ一覧の取得
         */
        public static function getPublishedGachas($category_code, $search_key=null )
        {

            # カテゴリー
            $gacha_category = GachaCategory::where('code_name',$category_code)
            ->where('is_published',true)->first();


            # ログインユーザーの会員ランク表示
            $user = Auth::check() ? Auth::user() : null;
            $user_rank_id = $user && $user->now_rank ? $user->now_rank->rank_id : null;




            # ID配列を指定して、ガチャの取得
            $query = Gacha::query();


                ## 売り切れは下
                $query->orderBy('is_sold_out');


                ## 時間帯の指定

                    $now = now()->copy()->addMinutes(30);//表示時間(30分前)
                    // $now = now();//表示時間(30分前)
                    $now_time = $now->format('H:i');//現在時刻

                    $query->where(function ($query) use ($now_time) {
                        $query->where('is_over_date', 1)//日を跨ぐ時間帯
                        ->where(function ($query) use ($now_time) {
                            $query->where('min_time', '<=', $now_time)
                            ->orwhere('max_time', '>', $now_time)
                            ;
                        })
                        ;

                    })
                    ->orWhere(function ($query) use ($now_time) {
                        $query->where('is_over_date', 0)//日中の時間帯
                        ->where(function ($query) use ($now_time) {
                            $query->where('min_time', '<=', $now_time)
                            ->where('max_time', '>', $now_time)
                            ;
                        });
                    });


                ## 会員ランク限定ガチャ（ログインユーザーの会員ランク表示）
                $query->where( function($query) use($user_rank_id)
                {
                    $query->where('user_rank_id',null); //全ての会員
                    if( $user_rank_id!==null ){
                        $query->orWhere('user_rank_id', $user_rank_id ); //ログインユーザーの会員ランク
                    }
                });


                ## 新規会委員のみ表示のガチャ(それ以外は非表示)
                if( !(Auth::check() && !Auth::user()->sevendays_affter_registar) )
                {
                    $query->where('type','<>','only_new_user');
                }



                ## 並び替え・絞り込み
                switch ($search_key) {

                    //* 人気順(過去1週間) */
                    case 'desc_popularity':
                        $oneWeekAgo = now()->subWeek(); // 過去1週間
                        $query->withSum(['user_gacha_histories' => function ($query) use ($oneWeekAgo) {
                            $query->where('created_at', '>=', $oneWeekAgo);
                        }], 'play_count')
                        ->orderByDesc('user_gacha_histories_sum_play_count')
                        ->get();
                        break;

                    //* 高ポイント順 */
                    case 'desc_point':
                        $query->orderByDesc('one_play_point');
                        $query->orderByDesc('published_at');
                        break;

                    //* 低ポイント順 */
                    case 'asc_point':
                        $query->orderBy('one_play_point');
                        $query->orderByDesc('published_at');
                        break;

                    //* 会員ランク限定 */
                    case 'user_rank':
                        $query->where( function($query) use($user_rank_id)
                        {
                            $query->where('user_rank_id','<>',null);//限定ガチャ
                            if( $user_rank_id!=null ){
                                $query->orWhere('user_rank_id', $user_rank_id ); //ログインユーザーの会員ランク
                            }
                        });
                        $query->orderByDesc('published_at');
                        break;

                    //* 1回or10回限定 */
                    case 'one_chance':
                        $query->where('type','one_chance');
                        $query->orderByDesc('published_at');
                        break;

                    //* 一回限定 */
                    case 'one_time':
                        $query->where('type','one_time');
                        $query->orderByDesc('published_at');
                        break;

                    //* １日１回 */
                    case 'only_oneday':
                        $query->where('type','only_oneday');
                        $query->orderByDesc('published_at');
                        break;

                    //* 全ての限定 */
                    case 'other_types':

                        $query->where( function($query) use($user_rank_id)
                        {
                            $query->where('type','<>','nomal');//通常ガチャを除く
                            $query->where('type','<>','no_custom');//カスタムボタンなしガチャを除く
                            $query->where('type','<>','max_custom');//カスタムボタン上限あり

                            if( $user_rank_id!=null ){
                                $query->orWhere('user_rank_id', $user_rank_id ); //ログインユーザーの会員ランク
                            }
                        });
                        $query->orderByDesc('published_at');
                        break;

                    //* 古い順 */
                    case 'asc_created':
                        $query->orderBy('published_at');
                        break;

                    //* 新着順 */
                    default:
                        $query->orderByDesc('published_at');
                        break;
                }


                # カテゴリー絞り込み
                if( $gacha_category ){
                    $query->where('category_id', $gacha_category->id);
                }

                ## 公開中のみ
                // $query->where('published_at', '<=', now());
                $query->where('published_at','<',now()->copy()->addMinutes(30));//30分前 新規カウントダウン
                // $query->where('published_at','<',now()->copy()->addDays(3));//3日前　新規カウントダウン
                $query->where('published_at', '<>', null);

                ## 公開中のカテゴリーのみ
                $category_ids = GachaCategory::where('is_published',1) //公開中
                ->get()->pluck('id')->toArray();
                $query->whereIn('category_id',$category_ids);


            return $query->orderByDesc('created_at');
        }


        /**
         * API用の追加データ
        */
        public static function addApiGachaData($gachas)
        {
            foreach ($gachas as $gacha)
            {
                /* 画像 */
                $gacha->route      = $gacha->route;                  //
                $gacha->ratio      = config('app.gacha_card_ratio'); //画像比率
                $gacha->image_path = $gacha->image_path;             //
                $gacha->type       = $gacha->type;                   //ガチャの種類

                $gacha->i_time                 = $gacha->initial_time; //カウントダウン時間
                $gacha->limitted_i_time        = $gacha->initial_timezone;//時間帯限定
                $gacha->published_at_format    = $gacha->published_at > now()
                ? $gacha->published_at->format('Y/m/d H:i:s') : null;//(新作ガチャ)カウントダウン用日時

                $gacha->add_chance_image_path  = $gacha->add_chance_image_path;//アド確定予告画像パス
                $gacha->add_chance_count       = $gacha->add_chance_count;     //天井系ガチャのアド確定までの回転数

                $gacha->have_user_rank         = $gacha->have_user_rank;       //個人のプレイ数の商品登録
                $gacha->user_played_count      = $gacha->user_played_count;    //ログインユーザー個人のプレイ数

                $gacha->img_path_one_chance    = $gacha->img_path_one_chance;   //ワンチャンス限定
                $gacha->img_path_one_time      = $gacha->img_path_one_time;     //一回限定
                $gacha->img_path_only_oneday   = $gacha->img_path_only_oneday;  //1日一回限定
                $gacha->img_path_only_new_user = $gacha->img_path_only_new_user;//新規会委員限定
                $gacha->img_path_user_rank     = $gacha->img_path_user_rank;    //会員ランク限定

                /* スライド */
                $gacha->slide_imgs      = $gacha->slide_imgs;      //スライド画像

                /* メーター */
                $gacha->user_rank_id    = $gacha->user_rank_id;    //
                $gacha->remaining_ratio = $gacha->remaining_ratio; //残数比率
                $gacha->remaining_count = $gacha->remaining_count; //残数
                $gacha->max_count       = $gacha->max_count;       //総口数
                $gacha->sponsor_ad      = $gacha->sponsor_ad;      //スポンサー
                $gacha->new_label_path  = $gacha->new_label_path;  //NEW ラベル
                $gacha->img_path_point  = $gacha->img_path_point;  //ポイントアイコン

                /* Playボタン */
                $params = [ 'category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key ];
                $gacha->r_action = route( 'gacha.play', $params ) ;                //ルート:play
                $gacha->r_costom = route( 'gacha.custom_count', $params ) ;        //ルート:カスタム
                $gacha->is_disabled_oneplay_btn = $gacha->is_disabled_oneplay_btn; //1回ガチャるボタンのdisabled
                $gacha->is_disabled_tenplay_btn = $gacha->is_disabled_tenplay_btn; //10連ガチャるボタンのdisabled
                $gacha->is_disabled_custom_btn  = $gacha->is_disabled_custom_btn;  //カスタムボタンのdisabled



            }

            return $gachas;
        }
}
