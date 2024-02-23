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


            # ID配列を指定して、ガチャの取得
            $query = Gacha::query();

                //売り切れは下
                $query->orderBy('is_sold_out');

                // 新規会委員のみ表示のガチャ(それ以外は非表示)
                if( !(Auth::check() && !Auth::user()->sevendays_affter_registar) )
                {
                    $query->where('type','<>','only_new_user');
                }

                // 並び替え・絞り込み
                switch ($search_key) {

                    # 高ポイント順
                    case 'desc_point':
                        $query->orderByDesc('one_play_point');
                        $query->orderByDesc('published_at');
                        break;

                    # 低ポイント順
                    case 'asc_point':
                        $query->orderBy('one_play_point');
                        $query->orderByDesc('published_at');
                        break;

                    # 一回限定
                    case 'one_time':
                        $query->where('type','one_time');
                        $query->orderByDesc('published_at');
                        break;

                    # １日１回
                    case 'only_oneday':
                        $query->where('type','only_oneday');
                        $query->orderByDesc('published_at');
                        break;

                    # 全ての限定　
                    case 'other_types':
                        $query->where('type','<>','nomal');
                        $query->orderByDesc('published_at');
                        break;

                    # 古い順
                    case 'asc_created':
                        $query->orderBy('published_at');
                        break;

                    # 新着順
                    default:
                        $query->orderByDesc('published_at');
                        break;
                }

            return $query->orderByDesc('created_at')->find($id_array);


            // return Gacha::orderByDesc('published_at')
            // ->orderByDesc('created_at')
            // ->find($id_array);
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
                    'image'=> $slide_info->image_path,
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

                ['label'=>'新着順', 'key'=>'desc_crated'],
                ['label'=>'高額ポイント順', 'key'=>'desc_point'],
                ['label'=>'低額ポイント順', 'key'=>'asc_point'],
                ['label'=>'一回限定', 'key'=>'one_time'],
                ['label'=>'１日１回', 'key'=>'only_oneday'],
                ['label'=>'全ての限定', 'key'=>'other_types'],
            ];

            return $array;

            // if( !(Auth::check() && !Auth::user()->sevendays_affter_registar) )
            // {
            //     $query->where('type','<>','only_new_user');
            // }

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
            foreach ($countdown_gachas as $countdown_gacha) {
                $countdown_gacha->initial_time = now()->diff($countdown_gacha->published_at)->format('%H:%I:%S');
            }

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
        if( $gacha->key!=$key || !$gacha->published_at ){ return \App::abort(404); }

        return view('gacha.show.index', compact( 'gacha' ));
    }




    /**
     * PLAYガチャの結果表示
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\UserGachaHistory $user_gacha_history
     * @return \Illuminate\Http\Response
     */
    public function result($category_code, UserGachaHistory $user_gacha_history)
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

        return view('gacha.result',compact('gacha','user_gacha_history', 'page_title', 'bg_image'));
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

        # 次ののガチャ履歴(ガチャ履歴のユーザー==本人のとき)
        $next_gacha_history = Auth::check() && ($user_id == Auth::user()->id)
        ? UserGachaHistory::where('id','>',$id)
        ->where('user_id',$user_id)
        ->orderBy('created_at')
        ->first()
        : null;

        // dd($prev_gacha_history);





        # ページタイトル
        $page_title = '「'.$gacha->name.'」の結果';

        # 背景画像
        $bg_image = asset('storage/site/image/gacha/bg_result.jpg');


        return view('gacha.result_history',compact(
            'gacha','user_gacha_history', 'user_prizes', 'user', 'page_title', 'bg_image',
            'prev_gacha_history', 'next_gacha_history',
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
        $point = number_format( $point_history->value );
        $message = '合計'.$user_prizes->count()."点の商品を\n".$point."ptに交換しました。\n選択されなかった商品は、\n「取得した商品一覧」に移動します。";

        return redirect()->route('gacha.result', compact('category_code','user_gacha_history'))
        ->with('alert-warning',$message);
    }
}
