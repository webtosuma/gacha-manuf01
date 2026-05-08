<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminBackGroundController;
use Illuminate\Http\Request;
use App\Models\Gacha;
use App\Models\UserGachaHistory;
use App\Models\Movie;
use App\Services\Gacha\PlayService;
use App\Services\Gacha\PlayValidationService;
use App\Services\UserRankHistoryService;
/*
| =============================================
|  Admin ガチャ コントローラー PLAY コントローラー
| =============================================
*/
class GachaPlayController extends Controller
{
    /** サービスの登録 */
    public function __construct(
        protected PlayService $playService,
        protected PlayValidationService  $validationService,
        protected UserRankHistoryService $URankHistorySerice,
    ){}


    /**
     * PLAYガチャで遊ぶ
     * @param \Illuminate\Http\Request $request
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha //ガチャモデル
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function play(Request $request, $category_code, Gacha $gacha, $key)
    {
        # ガチャ情報取得(他のリクエストを待機)
        $gacha = Gacha::where('id', $gacha->id)
        ->where('key',$key)
        ->firstOrFail();//データなしの場合、404


        # バリデーション
        $message = $this->validationService->index( $request, $gacha, $admin=true );
        if( $message ){

            $request->session()->regenerateToken();// 二重送信防止

            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }



        # 抽選結果の取得
        $user_gacha_history = $this->playService->index($request, $gacha, $key);


        # ガチャ実行後の昇格の評価
        $user    = $request->user();
        $rank_up = $this->URankHistorySerice->EvaluationPlaied($user);


        # 二重送信防止
        $request->session()->regenerateToken();


        # スポンサー広告ガチャのとき
        if( $gacha->sponsor_ad ){
            return redirect()->route('admin.gacha.sponsor_ad.movie',compact('user_gacha_history', 'rank_up' ));
        }


        # viewの表示 ($user_gacha_history:ガチャ履歴, $movie_path:動画パス )
        return redirect()->route('admin.gacha.movie', compact('user_gacha_history', 'rank_up' ));
    }



    /**
    * 演出動画
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function movie(Request $request)
    {
        # ユーザーガチャ履歴
        $ugh_id   = $request->input('user_gacha_history');
        $user_gacha_history = UserGachaHistory::find($ugh_id );

        # 変数定義
        $rank_up  = $request->input('rank_up');
        $movie_id = $user_gacha_history->movie_id;

        # 動画パス
        $movie = Movie::find($movie_id);
        $movie_path = [
            'pc'      => $movie->pc,
            'mobile'  => $movie->mobile,
            'youtube' => $movie->youtube_url,
        ];


        # youtube動画
        if( $movie->youtube_url ){
            return view('admin.gacha.play_movie.youtube', compact('user_gacha_history', 'movie_path', 'rank_up' ) );
        }

        // return view('admin.gacha.play', compact('user_gacha_history', 'movie_path', 'rank_up' ));
        return view('admin.gacha.play_movie.index', compact('user_gacha_history', 'movie_path', 'rank_up' ) );
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
        $user = $request->user();
        // if( $user_gacha_history->user_id!=$user->id ){ return (404); }

        # ガチャ
        $gacha = $user_gacha_history->gacha;

        # ページタイトル
        $page_title = '「'.$gacha->name.'」の結果';

        # 背景画像
        // $bg_image = asset('storage/site/image/gacha/bg_result.jpg');
        $bg_image = AdminBackGroundController::getBgResult();

        # ユーザーランク:昇格の評価結果受け取り
        $rank_up = $request->rank_up;


        return view('admin.gacha.result',compact('gacha','user_gacha_history', 'page_title', 'bg_image', 'rank_up'));
    }


    // /**
    //  * 景品のポイント交換
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @param String $category_code      //カテゴリーコード名
    //  * @param  \App\Models\UserGachaHistory $user_gacha_history
    //  * @return \Illuminate\Http\Response
    //  */
    // public function exchange_points(
    //     Request $request, $category_code,
    //     UserGachaHistory $user_gacha_history
    // ){

    //     # 景品のポイント交換
    //     $data = UserPrizeController::ExchangePoints($request);
    //     $point_history = $data['point_history'];
    //     $user_prizes   = $data['user_prizes'];


    //     # ガチャ
    //     $gacha = $user_gacha_history->gacha;


    //     # メッセージ
    //     $point = number_format( $point_history->value );
    //     $message = '合計'.$user_prizes->count()."点の商品を\n".$point."ptに交換しました。\n選択されなかった商品は、\n「取得した商品一覧」に移動します。";

    //     return redirect()->route('admin.gacha.result', compact('category_code','user_gacha_history'))
    //     ->with('alert-warning',$message);
    // }

}
