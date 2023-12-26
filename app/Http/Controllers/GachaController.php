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
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function index( $category_code='all' )
    {
        # 表示できないページの処理
        $category = GachaCategory::where('code_name', $category_code)->first();
        if( $category_code!='all' && !$category ){ return \App::abort(404); }

        # 変数

            ## 背景画像
            $bg_image = $category ? $category->bg_image_path : GachaCategory::noImage();

            ## ガチャのカテゴリーグループ一覧
            $categories = GachaCategory::where('is_published',1) //公開中
            ->get();

            ## 表示できるガチャ一覧
            $gachas = self::getPublishedGachas( $category_code );
            // dd($gachas[0]->image_path);

            ## お知らせ
            $infomations =
            InfomationController::GetInfomationsQuery()
            ->limit(3)->get();;

            ## スライドお知らせ
            $slide_infos = Infomation::where('published_at','<=', now()) //非公開を除く
            ->where('is_slide',1)
            ->get();
        //

        # viewの表示
        return view('gacha.index', compact(
            'category_code', 'bg_image',  'categories', 'gachas', 'infomations', 'slide_infos',
         ) );

    }


        /**
         * ガチャ一覧で表示できるガチャ一覧の取得
         */
        public static function getPublishedGachas( $category_code )
        {
            $now = now()->toDateString();


            # 該当するガチャのID配列
            $id_array =  $category_code != 'all'

                // カテゴリーを指定
                ? DB::table('gacha_categories')
                ->join('gachas', 'gacha_categories.id', '=', 'gachas.category_id')
                ->where('gacha_categories.code_name', $category_code) //コードネームの指定
                ->where('gacha_categories.is_published', true)        //公開中のカテゴリー
                ->whereNotNull('gachas.published_at')                 //公開設定
                ->whereDate('gachas.published_at', '<=', $now)        //公開済み
                ->select('gachas.*')
                ->get()->pluck('id')->toArray()

                // 全てのカテゴリ
                : DB::table('gacha_categories')
                ->join('gachas', 'gacha_categories.id', '=', 'gachas.category_id')
                ->where('gacha_categories.is_published', true)        //公開中のカテゴリー
                ->whereNotNull('gachas.published_at')                 //公開設定
                ->whereDate('gachas.published_at', '<=', $now)        //公開済み
                ->select('gachas.*')
                ->get()->pluck('id')->toArray()
            ;


            # ID配列を指定して、ガチャの取得
            return Gacha::orderBy('published_at','desc')->find($id_array);
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
        if( $gacha->key!=$key ){ return \App::abort(404); }


        // dd( $gacha->play_count);


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


        return view('gacha.result',compact('gacha','user_gacha_history',));
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
        UserPrizeController::ExchangePoints($request);

        # ガチャ
        $gacha = $user_gacha_history->gacha;
        # メッセージ
        $message = '指定した景品をポイント交換しました。';

        return view('gacha.result',compact('gacha','user_gacha_history','message') )
        ->with('alert-warning',$message);

    }
}
