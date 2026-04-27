<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminBackGroundController;
use App\Http\Controllers\GachaController;
use App\Http\Controllers\GachaApiController;
use App\Http\Controllers\InfomationController;
use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Text;
use App\Models\ManufGachaTitle;
use App\Services\Manuf\GachaTitleService;
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


    
}
