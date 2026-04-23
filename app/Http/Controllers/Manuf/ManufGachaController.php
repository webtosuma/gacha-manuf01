<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminBackGroundController;
use App\Http\Controllers\GachaController;
use App\Http\Controllers\GachaApiController;
use App\Http\Controllers\InfomationController;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
use App\Models\GachaCategory;
use App\Models\Gacha;
// use App\Models\UserGachaHistory;
// use App\Models\UserPrize;
// use App\Models\PointHistory;
// use App\Models\Infomation;
// use App\Models\Movie;
use App\Models\Text;

use App\Models\ManufGachaTitle;
/*
| =============================================
|  Manufacturer:ガチャ コントローラー
| =============================================
*/
class ManufGachaController extends Controller
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
        # ガチャタイトル
        $gacha_title = ManufGachaTitle::where('code',$title_code)->first();

        # キーのチェック
        if(

            !isset($gacha_title)
            || $gacha_title->category->code_name!=$category_code
            || !$gacha_title->is_published//公開有無

        ){ return abort(404); }

        # 筐体
        $machines = $gacha_title->machines;

        # 背景画像
        $category = $gacha_title->category;
        $bg_image = $category && $category->bg_image_path
        ? $category->bg_image_path : AdminBackGroundController::getBgTop();

        # 表示できるガチャ一覧
        $category_code = $category->code_name;
        $query = GachaApiController::getPublishedGachas( $category_code, $search_key=null );

            ## イベントガチャ (is_event_gacha)
            $query->where('type','<>','event');
            // $query->where('type','event');

        $gachas = $query->paginate(6);

        return view('manuf.gacha.show', compact(
            'gacha_title',
            'machines',
            'gachas','category_code',
            'bg_image'
        ));
    }


    /**
     * ガチャタイトルのマシーン一覧
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function machines( $category_code, Gacha $gacha, $key)
    {
        # キーのチェック
        if( $gacha->key!=$key || !$gacha->published_at ){ return abort(404); }

        # 追加情報
        $gacha->price = 500;      //価格(税込)
        $gacha->waiting_count = 3;//購入待機数
        $gacha->resume = "テキストテキスト テキストテキスト テキストテキスト テキストテキスト テキストテキスト テキストテキスト ";

        # 背景画像
        $category = $gacha->category;
        $bg_image = $category && $category->bg_image_path
        ? $category->bg_image_path : AdminBackGroundController::getBgTop();



        return view('manuf.gacha.machines.index', compact(
            'gacha','bg_image'
        ));
    }

}
