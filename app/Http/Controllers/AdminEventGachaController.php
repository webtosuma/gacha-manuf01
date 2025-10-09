<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GachaPlayCreateUserPrizeMethod
as CreateUserPrize;
use App\Http\Controllers\GachaPlayController
as GachaPlay;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\GachaCategory;
use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\GachaRankMovie;
use App\Models\Movie;
/*
| =============================================
|  Admin イベントガチャ コントローラー
| =============================================
*/

class AdminEventGachaController extends Controller
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
            $bg_image = $category && $category->bg_image_path
            ? $category->bg_image_path : AdminBackGroundController::getBgEvent();

            ## ガチャのカテゴリーグループ一覧
            $categories = GachaCategory::userList()->get();

            ## カードサイズ
            $card_size = $request->card_size ? $request->card_size : null;

            ## 絞り込みキー
            $search_key = $request->search_key ? $request->search_key : 'desc_created';

            ## 検索キーワード
            $searchs = GachaController::getsearchs();


            ## お知らせ
            // $infomations =
            // InfomationController::GetInfomationsQuery()
            // ->limit(3)->get();


        # viewの表示
        return view('admin.event_gacha.index', compact(
            'category_code', 'category_name', 'bg_image',  'categories',
            'card_size', 'search_key',
            // 'infomations',
         ) );

    }



    /**
     * 詳細表示
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function show( $category_code, Gacha $gacha, $key)
    {
        # キーのチェック
        if( $gacha->key!=$key || !$gacha->published_at ){ return \App::abort(404); }

        ## 背景画像
        $category = $gacha->category;
        $bg_image = $category && $category->bg_image_path
        ? $category->bg_image_path : AdminBackGroundController::getBgEvent();

        ## 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $query = GachaApiController::getPublishedGachas( $category_code, $search_key=null );

            ## イベントガチャ (is_event_gacha)
            // $query->where('type','<>','event');
            $query->where('type','event');

        $gachas = $query->paginate(6);

        return view('admin.event_gacha.show.index', compact(
            'gacha',
            'gachas','category_code',
            'bg_image'
        ));
    }


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
        # 変数
        $user = Auth::user(); //ログインユーザー取得
        // $now_play_count = (int) $request->play_count;   //今回のプレイ回数
        $now_play_count = 1;
        $play_point = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
        $total_play_point = $now_play_count*$play_point;//合計使用ポイント
        $is_sold_out = (bool) $gacha->remaining_count < 1; //売り切れかどうか

        # キー認証
        if( $gacha->key!=$key ){ return \App::abort(404); }


        # ポイントが不足しているとき
        if( $total_play_point > $user->point ){
            $message = 'ポイントが不足しています。';
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        # ガチャ開始前チェック
        $message =  self::StartCheckMessage( $request, $gacha );
        if( $message ){
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }


        DB::beginTransaction();
        try {

            # ポイント履歴の登録
            $point_history = GachaPlay::CreatePointHistory( $total_play_point );

            # ガチャ履歴の登録
            $user_gacha_history = GachaPlay::CreateGachaHistory( $gacha, $point_history ,$now_play_count );

            # 当たりの選出・ユーザー取得商品の登録・残り商品の減算
            $randReminingGPIdArray = CreateUserPrize::index( $user_gacha_history );


            # ランダムで選出した、ガチャ商品の最大ランク
            $max_rank = GachaPlay::MaxRank($randReminingGPIdArray );

            # 動画パスの取得
            $movie = GachaPlay::MoviePath($gacha, $max_rank);

            DB::commit();


        } catch (\Exception $e) {

            Log::error($e);
            DB::rollback();
            $message = 'エラーが発生しました。';
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);

        }



        # 売り切れ登録
        $new_remaining_count = Gacha::find($gacha->id)->remaining_count;
        if( $new_remaining_count < 1 ){
            $gacha->update([
                'sold_out_at' => now(),
                'is_sold_out' => 1,
            ]);
        }


        // 二重送信防止
        $request->session()->regenerateToken();



        # viewの表示 ($user_gacha_history:ガチャ履歴, $movie_path:動画パス )
        return redirect()->route('event.gacha.movie', compact('user_gacha_history', 'movie' ));
    }



        /**
         * ガチャ開始前のチェック
         * @param \Illuminate\Http\Request $request
         * @param  \App\Models\Gacha  $gacha //ガチャモデル
         * @return String //エラーメセージ
         */
        public function StartCheckMessage( $request, $gacha )
        {
            # 変数
            $user = Auth::user(); //ログインユーザー取得
            $now_play_count = (int) $request->play_count;   //プレイ回数
            $play_point = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
            $total_play_point = $now_play_count*$play_point;//合計使用ポイント
            $remaining_count  = (int) $gacha->remaining_count; //残りのプレイできる回数
            $is_sold_out = (bool) $gacha->remaining_count < 1; //売り切れかどうか



            # ログインしていないとき
            if( !Auth::check() ){
                return 'ガチャを始めるには、ログインが必要です';
            }

            # 売り切れ
            else if( $is_sold_out ){
                return '売り切れです';
            }

            # ポイントが不足しているとき
            else if( $total_play_point > $user->point ){
                return 'ポイントが不足しています';
            }

            # 商品数が、プレイ回数より少ないとき
            else if( $now_play_count > $remaining_count ){
                return '残りの商品数が少ないため、複数回ガチャをすることができません';
            }

            return null;
        }



    /**
    * 演出動画
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function movie(Request $request)
    {
        # 変数定義
        $ugh_id   = $request->input('user_gacha_history');
        $movie_id = $request->input('movie');
        $rank_up  = null;

        # ユーザーガチャ履歴
        $user_gacha_history = UserGachaHistory::find($ugh_id );

        # 動画パス
        $movie = Movie::find($movie_id);
        $movie_path = [
            'pc'      => $movie->pc,
            'mobile'  => $movie->mobile,
            'youtube' => $movie->youtube_url,
        ];


        # youtube動画
        if( $movie->youtube_url ){
            return view('admin.event_gacha.play_movie.youtube', compact('user_gacha_history', 'movie_path', 'rank_up' ) );
        }

        // return view('admin.event_gacha.play', compact('user_gacha_history', 'movie_path', 'rank_up' ));
        return view('admin.event_gacha.play_movie.index', compact('user_gacha_history', 'movie_path', 'rank_up' ) );
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
        // if( $user_gacha_history->user_id!=$user->id ){ return \App::abort(404); }

        # ガチャ
        $gacha = $user_gacha_history->gacha;

        # ページタイトル
        $page_title = '「'.$gacha->name.'」の結果';

        # 背景画像
        // $bg_image = asset('storage/site/image/gacha/bg_result.jpg');
        $bg_image = AdminBackGroundController::getBgResult();

        # ユーザーランク:昇格の評価結果受け取り
        $rank_up = null;

        # 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $query = GachaApiController::getPublishedGachas( $category_code, $search_key=null );

            ## イベントガチャ (is_event_gacha)
            // $query->where('type','<>','event');
            $query->where('type','event');

        $gachas = $query->paginate(6);


        return view('admin.event_gacha.result',compact('gacha','user_gacha_history', 'page_title', 'bg_image', 'rank_up'));
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
        $user = $user_gacha_history->user;
        $user_prizes = $user_gacha_history->user_prizes;

        # ポイント履歴の登録
        if( $user_prizes->count()>0 ){
            $point_history = new PointHistory([
                'user_id'   => $user->id,    //ユーザー　リレーション
                'value'     => 0, //ポイント数
                'reason_id' => 12, // '商品のポイント交換',
            ]);
            $point_history->save();

        }

        # ユーザー取得商品情報の更新
        foreach ($user_prizes as $user_prize) {
            $user_prize->point_history_id = $point_history->id;
            $user_prize->save();
        }

        # 二重送信防止
        $request->session()->regenerateToken();


        # ガチャ
        $gacha = $user_gacha_history->gacha;


        # メッセージ
        $message = "商品を受け取り登録しました。";

        return redirect()->route('event.gacha.result', compact('category_code','user_gacha_history'))
        ->with('alert-warning',$message);
    }
}
