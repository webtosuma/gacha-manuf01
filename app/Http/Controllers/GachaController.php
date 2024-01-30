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

            ## カテゴリー名（ページタイトル）
            $category_name = $category ? $category->name : 'すべて';

            ## 背景画像
            $bg_image = $category ? $category->bg_image_path : GachaCategory::noImage();

            ## ガチャのカテゴリーグループ一覧
            $categories = GachaCategory::where('is_published',1) //公開中
            ->orderBy('created_at')
            ->get();

            ## 表示できるガチャ一覧
            $gachas = self::getPublishedGachas( $category_code );
            // dd($gachas[0]->image_path);

            ## お知らせ
            $infomations =
            InfomationController::GetInfomationsQuery()
            ->limit(3)->get();

            ## スライドお知らせ
            $slide_infos = InfomationController::GetInfomationsQuery()
            ->where('is_slide',1)
            ->limit(3)->get();;


            ## スライド
            $slides = self::getSlides($gachas);

        //

        # viewの表示
        return view('gacha.index', compact(
            'category_code', 'category_name', 'bg_image',  'categories', 'gachas', 'infomations',
            // 'slide_infos',
            'slides',
         ) );

    }


        /**
         * ガチャ一覧で表示できるガチャ一覧の取得
         */
        public static function getPublishedGachas( $category_code )
        {
            $now = now()->toDateTimeString();

            # 該当するガチャのID配列
            $id_array =  $category_code != 'all'

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


            # ID配列を指定して、ガチャの取得
            return Gacha::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->find($id_array);
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
            ->limit(3)->get();
            foreach ($slide_infos as $slide_info) {
                $slides[] = [
                    'href' => route('infomation.show',$slide_info),
                    'image'=> $slide_info->image_path,
                ];
            }
            //ガチャ
            foreach ($gachas as $gacha) {
                if($gacha->is_slide){
                    $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key];
                    $slides[] = [
                        'href' => route('gacha',$params),
                        'image'=> $gacha->image_path
                    ];
                }
            }


            return $slides;
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
