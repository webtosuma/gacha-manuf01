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
|  ガチャ コントローラー
| =============================================
*/
class GachaController extends Controller
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
            $searchs = self::getsearchs();

            ## 表示できるガチャ一覧
            $gachas = self::getPublishedGachas( $category_code, $search_key );
            // dd($gachas[0]->user_rank->image_path);
            // dd($gachas[1]->user_rank->id);




            ## カウントダウンガチャ
            $countdown_gachas = self::getCountdownGachas($category_code);

            ## お知らせ
            $infomations =
            InfomationController::GetInfomationsQuery()
            ->limit(3)
            ->get();

            ## スライド
            $slides = self::getSlides($gachas);

        //

        # viewの表示
        return view('gacha.index', compact(
            'category_code', 'category_name', 'bg_image',  'categories', 'card_size',
            'search_key', 'searchs', 'gachas', 'countdown_gachas', 'infomations',
            'slides',
         ) );

    }
        /*
         * カテゴリーに該当するガチャのID配列の取得
        */
        public static function getGachaIdOfCategory($category_code)
        {
            $now = now()->toDateTimeString();

            return  $category_code != 'all'

                // カテゴリーを指定
                ? DB::table('gacha_categories')
                ->join('gachas', 'gacha_categories.id', '=', 'gachas.category_id')
                ->where('gacha_categories.code_name', $category_code) //コードネームの指定
                ->where('gacha_categories.is_published', true)        //公開中のカテゴリー
                ->whereNotNull('gachas.published_at')                 //公開設定
                ->where('gachas.published_at', '<=', $now)        //公開済み
                ->select('gachas.*')
                ->get()->pluck('id')->toArray()

                // 全てのカテゴリ
                : DB::table('gacha_categories')
                ->join('gachas', 'gacha_categories.id', '=', 'gachas.category_id')
                ->where('gacha_categories.is_published', true)        //公開中のカテゴリー
                ->whereNotNull('gachas.published_at')                 //公開設定
                ->where('gachas.published_at', '<=', $now)        //公開済み
                ->select('gachas.*')
                ->get()->pluck('id')->toArray()
            ;

        }


        /**
         * ガチャ一覧で表示できるガチャ一覧の取得
         */
        public static function getPublishedGachas($category_code, $search_key=null )
        {

            # カテゴリーに該当するガチャのID配列
            $id_array = self::getGachaIdOfCategory($category_code);


            # ログインユーザーの会員ランク表示
            $user = Auth::check() ? Auth::user() : null;
            $user_rank_id = $user && $user->now_rank ? $user->now_rank->rank_id : null;




            # ID配列を指定して、ガチャの取得
            $query = Gacha::query();


                ## 公開中のみ
                // $query->where('published_at', '<=', now());

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


            return $query->orderByDesc('created_at')->find($id_array);
            // return $query->whereNotNull('published_at')
            // ->where('published_at', '<=', now())
            // ->orderByDesc('created_at')->get();


        }



        /**
         * スライド情報
        */
        public function getSlides($gachas)
        {
            $slides = [];

            // お知らせ
            $slide_infos = InfomationController::GetInfomationsQuery()
            ->where('is_slide',1)
            // ->limit(3)
            ->get();

            foreach ($slide_infos as $slide_info) {
                $slides[] = [
                    'type' => 'info',
                    'href' => route('infomation.show',$slide_info),
                    'image'=> $slide_info->image_path ??  asset( 'storage/site/image/no_image.jpg' ),
                ];
            }
            //ガチャ
            foreach ($gachas as $gacha) {
                if($gacha->is_slide){
                    $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key];
                    $slides[] = [
                        'type' => 'gacha',
                        'href' => route('gacha',$params),
                        'image'=> $gacha->image_path,
                        'gacha'=> $gacha,
                    ];
                }
            }

            return $slides;
        }



        /**
         * 検索キーワード
        */
        public function getsearchs()
        {
            $array = [
                // ['label'=>'新規会員限定', 'key'=>'only_new_user'],

                ['label'=>'新着順',        'key'=>'desc_crated'],
                ['label'=>'高額ポイント順', 'key'=>'desc_point'],
                ['label'=>'低額ポイント順', 'key'=>'asc_point'],
                ['label'=>'会員ランク限定', 'key'=>'user_rank'],
                ['label'=>'一回限定',      'key'=>'one_time'],
                ['label'=>'１日１回',      'key'=>'only_oneday'],
                ['label'=>'全ての限定',    'key'=>'other_types'],
            ];

            return $array;
        }



        /*
         * カウントダウンガチャ
        */
        public static function getCountdownGachas($category_code)
        {
            # カテゴリーID
            $category_id = $category_code == 'all' ? null
            : GachaCategory::where('code_name',$category_code)->first()->id;



            # ID配列を指定して、ガチャの取得
            $query = Gacha::query();

                // カテゴリーIDの指定
                if($category_id){ $query->where('category_id',$category_id);}

                $query->where('published_at','>',now());//予約中

                $query->where('published_at','<',now()->copy()->addMinutes(30));//30分前


            $countdown_gachas = $query->orderByDesc('created_at')->get();


            # データの追加(カウントダウンの時間)
            // foreach ($countdown_gachas as $countdown_gacha) {
            //     $countdown_gacha->initial_time = now()->diff($countdown_gacha->published_at)->format('%H:%I:%S');
            // }

            return $countdown_gachas;
        }
    //




    /**
     * 詳細表示
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function show( $category_code, Gacha $gacha, $key)
    {
        // dd($gacha->is_show_timezone);

        # キーのチェック
        if( $gacha->key!=$key || !$gacha->published_at ){ return \App::abort(404); }

        # 会員ランク専用ガチャ：ログインユーザーのランクと異なれば非表示
        $user = Auth::check() ? Auth::user() : null;
        $user_rank_id = $user && $user->now_rank ? $user->now_rank->rank_id : null;
        if(
            $gacha->user_rank_id != null
            && $gacha->user_rank_id != $user_rank_id

        ){ return \App::abort(401); }


        ## 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $gachas = self::getPublishedGachas( $category_code, null );

        return view('gacha.show.index', compact(
            'gacha',
            'gachas','category_code'
        ));
    }



    /**
     * 詳細表示
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function custom_count( $category_code, Gacha $gacha, $key)
    {
        # キーのチェック
        if( $gacha->key!=$key || !$gacha->published_at ){ return \App::abort(404); }

        # 会員ランク専用ガチャ：ログインユーザーのランクと異なれば非表示
        $user = Auth::check() ? Auth::user() : null;
        $user_rank_id = $user && $user->now_rank ? $user->now_rank->rank_id : null;
        if(
            $gacha->user_rank_id != null
            && $gacha->user_rank_id != $user_rank_id

        ){ return \App::abort(401); }


        ## 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $gachas = self::getPublishedGachas( $category_code, null );

        return view('gacha.custom_count', compact(
            'gacha',
            'gachas','category_code'
        ));
    }



    /**
     * PLAYガチャの結果表示
     *
     * @param Request $request
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\UserGachaHistory $user_gacha_history
     * @return \Illuminate\Http\Response
     */
    public function result(Request $request, $category_code, UserGachaHistory $user_gacha_history)
    {
        # ユーザの結果のみを表示
        $user = Auth::user();
        if( $user_gacha_history->user_id!=$user->id ){ return \App::abort(404); }

        # ガチャ
        $gacha = $user_gacha_history->gacha;

        # ページタイトル
        $page_title = '「'.$gacha->name.'」の結果';

        # 背景画像
        $bg_image = asset('storage/site/image/gacha/bg_result.jpg');

        # ユーザーランク:昇格の評価結果受け取り
        $rank_up = $request->rank_up;


        ## 表示できるガチャ一覧
        // $category_code = $gacha->category->code_name;
        $gachas = self::getPublishedGachas( $category_code, null );

        return view('gacha.result',compact(
            'gacha','user_gacha_history', 'page_title', 'bg_image', 'rank_up',
            'gachas','category_code'
        ));
    }



    /**
     * PLAYガチャの結果履歴の表示
     * @param String $history_key      //カテゴリーコード名
     * @return \Illuminate\Http\Response
     */
    public function result_history($history_key)
    {
        # ガチャ履歴情報の取得
        list($id,$created_at,$user_id) = explode('-',$history_key);
        $user_gacha_history = UserGachaHistory::where('id',$id)
        ->where('user_id',$user_id)
        ->where('created_at',$created_at)
        ->first();
        if( !$user_gacha_history ){ return \App::abort(404); }

        # ガチャ
        $gacha = $user_gacha_history->gacha;


        # 取得商品
        $user_prizes = $user_gacha_history->user_prizes;

        # ユーザー情報
        $user = $user_gacha_history->user;

        // dd(Auth::check());
        # 前のガチャ履歴(ガチャ履歴のユーザー==本人のとき)
        $prev_gacha_history = Auth::check() && ($user_id == Auth::user()->id)
        ? UserGachaHistory::where('id','<',$id)
        ->where('user_id',$user_id)
        ->orderByDesc('created_at')
        ->first()
        : null;

        # 次のガチャ履歴(ガチャ履歴のユーザー==本人のとき)
        $next_gacha_history = Auth::check() && ($user_id == Auth::user()->id)
        ? UserGachaHistory::where('id','>',$id)
        ->where('user_id',$user_id)
        ->orderBy('created_at')
        ->first()
        : null;


        # ページタイトル
        $page_title = '「'.$gacha->name.'」の結果';

        # 背景画像
        $bg_image = asset('storage/site/image/gacha/bg_result.jpg');




        ## 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $gachas = self::getPublishedGachas( $category_code, null );


        return view('gacha.result_history',compact(
            'gacha','user_gacha_history', 'user_prizes', 'user', 'page_title', 'bg_image',
            'prev_gacha_history', 'next_gacha_history',
            'gachas','category_code'
        ) );
    }



    /**
     * 景品のポイント交換
     *
     * @param \Illuminate\Http\Request $request
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\UserGachaHistory $user_gacha_history
     * @return \Illuminate\Http\Response
     */
    public function exchange_points(
        Request $request, $category_code,
        UserGachaHistory $user_gacha_history
    ){
        # 景品のポイント交換
        $data = UserPrizeController::ExchangePoints($request);
        $point_history = $data['point_history'];
        $user_prizes   = $data['user_prizes'];


        # ガチャ
        $gacha = $user_gacha_history->gacha;


        # メッセージ
        if( $user_prizes->count()>0 ){

            $point = number_format( $point_history->value );
            $message = '合計'.$user_prizes->count()."点の商品を\n".$point."ptに交換しました。\n選択されなかった商品は、\n「取得した商品一覧」に移動します。";

            return redirect()->route('gacha.result', compact('category_code','user_gacha_history'))
            ->with('alert-warning',$message);

        }
        else{
            $message = '不正な処理を検知しました。';

            return  redirect()->route('gacha.result', compact('category_code','user_gacha_history'))
            ->with(['alert-danger'=>$message, 'icon'=>'bi-exclamation-circle' ]);
        }
    }
}
