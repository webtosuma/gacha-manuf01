<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\PointHistory;

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

        #


        # 変数
            ## 背景画像
            $bg_image = $category ? $category->bg_image_path : GachaCategory::noImage();

            ## ガチャのカテゴリーグループ一覧
            $categories = GachaCategory::where('is_published',1) //公開中
            ->get();

            ## 表示できるガチャ一覧
            $gachas = self::getPublishedGachas( $category_code );
            // dd($gachas[0]->image_path);
        //

        # viewの表示
        return view('gacha.index', compact( 'category_code', 'bg_image',  'categories', 'gachas' ) );
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
            return Gacha::find($id_array);
        }





    /**
     * 詳細表示
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function show($gacha)
    {
        return view('gacha.show');
    }

    /**
     * PLAYガチャで遊ぶ
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     *
     * @return \Illuminate\Http\Response
     */
    public function play(Request $request)
    {
        # 変数
        $user = Auth::user(); //ログインユーザー取得
        $play_point = 100;    //ガチャの1回プレー使用ポイント　＊<------あとで修正
        $play_count = $request->play_count; //プレイ数

        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,          //ユーザー　リレーション
            'value'     => - ( $play_point * $play_count ), //使用ポイント数
            'reason_id' => 21 //入出理由ID
        ]);
        $point_history->save();

        return view('gacha.play');
    }

    /**
     * PLAYガチャのガチャカの結果表示
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     *
     * @return \Illuminate\Http\Response
     */
    public function result(Request $request)
    {
        return view('gacha.result');
    }
}
