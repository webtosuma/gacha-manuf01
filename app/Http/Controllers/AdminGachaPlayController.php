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
use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\GachaRankMovie;
use App\Models\Movie;
/*
| =============================================
|  Admin ガチャ コントローラー PLAY コントローラー
| =============================================
*/
class AdminGachaPlayController extends Controller
{
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
        // dd($request->all());


        # 変数
        $user = Auth::user(); //ログインユーザー取得
        $play_count = (int) $request->play_count;   //プレイ回数
        $play_count = $gacha->sponsor_ad ? 1 : (int) $play_count;//(広告ガチャのとき）プレイ回数=>1

        $play_point = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
        $total_play_point = $play_count*$play_point;//合計使用ポイント
        $remaining_count  = (int) $gacha->remaining_count; //残りのプレイできる回数
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
            $user_gacha_history = GachaPlay::CreateGachaHistory( $gacha, $point_history ,$play_count );

            # 当たりの選出・ユーザー取得商品の登録・残り商品の減算
            $randReminingGPIdArray = CreateUserPrize::index( $user_gacha_history, $play_count );


            # ランダムで選出した、ガチャ商品の最大ランク
            $max_rank = GachaPlay::MaxRank($randReminingGPIdArray );

            # 動画パスの取得
            $movie_path = GachaPlay::MoviePath($gacha, $max_rank);


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


        # ユーザーランク:昇格の評価
        $rank_up = false;
        if( $user->now_rank && env('NEW_TICKET_SISTEM',false) )
        {
           $rank_up = UserRankHistoryController::CreateRankUpHistory( $user, now(), $user->now_rank );
        }


        // 二重送信防止
        $request->session()->regenerateToken();


        # スポンサー広告ガチャのとき
        if( $gacha->sponsor_ad ){
            return redirect()->route('admin.gacha.sponsor_ad.movie',compact('user_gacha_history', 'rank_up' ));
        }


        # viewの表示 ($user_gacha_history:ガチャ履歴, $movie_path:動画パス )
        return view('admin.gacha.play', compact('user_gacha_history', 'movie_path', 'rank_up' ));
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
            $play_count = (int) $request->play_count;   //プレイ回数
            $play_point = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
            $total_play_point = $play_count*$play_point;//合計使用ポイント
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
            else if( $play_count > $remaining_count ){
                return '残りの商品数が少ないため、複数回ガチャをすることができません';
            }

            return null;
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


        return view('admin.gacha.result',compact('gacha','user_gacha_history', 'page_title', 'bg_image', 'rank_up'));
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

        return redirect()->route('admin.gacha.result', compact('category_code','user_gacha_history'))
        ->with('alert-warning',$message);
    }
}
