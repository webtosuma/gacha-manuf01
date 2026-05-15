<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminBackGroundController;
use App\Http\Controllers\GachaController;
use App\Http\Controllers\GachaApiController;
use App\Http\Controllers\InfomationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Movie;
use App\Models\Text;
use App\Models\ManufGachaTitle;
use App\Models\ManufPurchaseItem;
use App\Services\Manuf\GachaTitleService;
use App\Services\Manuf\ValidationService;
/*
| =============================================
|  Manufacturer:ガチャタイトル コントローラー
| =============================================
*/
class GachaTitleController extends Controller
{
    # サービスの登録
    public function __construct(
        protected GachaTitleService $gachaTitleService,
        protected ValidationService $validationService, // バリデーションサービス
    ) {}


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
        if( $category_code!='all' && !$category ){ return abort(404); }


        # 変数

            ## カテゴリー名（ページタイトル）
            $category_name = $category ? $category->name : 'すべて';

            ## 背景画像
            // $bg_image = $category ? $category->bg_image_path : AdminBackGroundController::getBgTop();
            $bg_image = $category && $category->bg_image_path
            ? $category->bg_image_path : AdminBackGroundController::getBgTop();

            ## ガチャのカテゴリーグループ一覧
            $categories = GachaCategory::userList()->get();

            ## カードサイズ
            $text_model = new Text();
            $card_size = $request->card_size ? $request->card_size : $text_model->gacha_settings_size;

            ## 絞り込みキー
            $search_key = $request->search_key ? $request->search_key : 'desc_published_at';

            ## 検索キーワード
            $searchs = GachaController::getsearchs();


            ## お知らせ
            $infomations =
            InfomationController::GetInfomationsQuery()
            ->whereNotIn( 'type', ['ec'] )
            ->limit(3)->get();

            ## スライド
            $query = GachaApiController::getPublishedGachas( $category_code, $search_key );
            $gachas = $query->where('is_slide',1)//スライドのみ
            ->where('is_sold_out',0)             //売り切れを除く
            ->where('published_at','<',now())    //予告を除く
            ->limit(10)->get();
            $slides = GachaController::getSlides($gachas);

        //


        $gacha_titles = ManufGachaTitle::get();


        # viewの表示
        return view('manuf.gacha.index', compact(
            'category_code', 'category_name', 'bg_image',  'categories', 'card_size',
            'search_key', 'searchs',
            'infomations',
            'slides',
            'gacha_titles',
         ) );

    }



    /**
     * 詳細表示
     * @param String $category_code //カテゴリーコード名
     * @param String $title_code    //ガチャタイトル・キー
     * @return \Illuminate\Http\Response
     */
    public function show( $category_code, $title_code)
    {
        # ガチャタイトル詳細情報の取得/カテゴリーのコードチェック
        $gacha_title = $this->gachaTitleService->getGachaTitle($title_code, $category_code);

        # バリデーションチェック
        $this->validationService->checkeGachaTitle($gacha_title);

        # 筐体
        $machines = $gacha_title->machines;

        # 背景画像
        $category = $gacha_title->category;
        $bg_image = $category && $category->bg_image_path
        ? $category->bg_image_path : AdminBackGroundController::getBgTop();


        return view('manuf.gacha.show', compact(
            'gacha_title',
            'machines',
            'category_code',
            'bg_image'
        ));
    

    }


    
    /**
    * 演出動画 ManufPurchaseItem
    *
    * @param String $item_code
    * @return \Illuminate\Http\Response
    */
    public function movie( $item_code )
    {
        # ユーザの結果のみを表示
        $user = Auth::user();
    
        # 購入アイテム
        $item = ManufPurchaseItem::where('code',$item_code)
        ->where('user_id',$user->id)
        ->firstOrFail();//データなしの場合、404


        # ガチャ履歴
        $gacha_history = $item->gacha_history;

        # 変数定義
        $movie_id = $gacha_history->movie_id;
        $rank_up  = null;


        # 動画パス
        $movie = Movie::find($movie_id);
        $movie_path = $movie ? [
            'pc'      => $movie->pc,
            'mobile'  => $movie->mobile,
            'youtube' => $movie->youtube_url,
        ] : [
            'pc'      => null,
            'mobile'  => null,
            'youtube' => '',
        ];


        # パラメーター
        $param = compact('item','gacha_history', 'movie_path', 'rank_up'  );

        # youtube動画
        if( $movie && $movie->youtube_url ){
            return view('manuf.gacha.movie.youtube',$param  );
        }

        return view('manuf.gacha.movie.index',$param );
    }



    /**
     * 結果表示
     *
     * @param Request $request
     * @param String  $item_code
     * @return \Illuminate\Http\Response
     */
    public function result( Request $request, $item_code )
    {
        # ユーザの結果のみを表示
        $user = Auth::user();
    
        # 購入アイテム
        $item = ManufPurchaseItem::where('code',$item_code)
        ->where('user_id',$user->id)
        ->firstOrFail();//データなしの場合、404

        # ガチャ履歴
        $gacha_history = $item->gacha_history;

        # ガチャ
        $gacha = $gacha_history->gacha;

        # ページタイトル
        // $page_title = '「'.$gacha->name.'」の結果';
        $page_title = '抽選結果';

        # 背景画像
        $bg_image = AdminBackGroundController::getBgResult();

        # ユーザーランク:昇格の評価結果受け取り
        $rank_up = $request->rank_up;


        ## 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $query = GachaApiController::getPublishedGachas( $category_code, $search_key=null );

            ## イベントガチャ (is_event_gacha)
            $query->where('type','<>','event');
            // $query->where('type','event');

        $gachas = $query->paginate(6);


        return view('manuf.gacha.result',compact(
            'gacha','gacha_history', 'page_title', 'bg_image', 'rank_up',
            'gachas','category_code'
        ));
    }


}
