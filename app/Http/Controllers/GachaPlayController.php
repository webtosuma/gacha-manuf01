<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GachaPlayCreateUserPrizeMethod
as CreateUserPrize;

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
|  ガチャ PLAY コントローラー
| =============================================
*/
class GachaPlayController extends Controller
{

    /**
     * ガチャカで遊ぶ
     * @param \Illuminate\Http\Request $request
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha //ガチャモデル
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function play(Request $request, $category_code, Gacha $gacha, $key)
    {

        DB::beginTransaction();
        try {

            # ガチャ情報取得(他のリクエストを待機)
            $gacha = Gacha::where('id',$gacha->id)
            ->lockForUpdate()//他のリクエストを待機
            ->first();

            # 変数
            $user = Auth::user(); //ログインユーザー取得
            $now_play_count = (int) $request->play_count;   //プレイ回数
            $now_play_count = $gacha->sponsor_ad ? 1 : (int) $now_play_count;//(広告ガチャのとき）プレイ回数=>1
            $play_point = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
            $total_play_point = $now_play_count*$play_point;//合計使用ポイント
            $is_sold_out = (bool) $gacha->remaining_count < 1; //売り切れかどうか

            # キー認証
            if( $gacha->key!=$key ){ return \App::abort(404); }


            # ポイントが不足しているとき
            if( $total_play_point > $user->point ){
                $params = ['gacha_id'=>$gacha->id,'play_count'=>$now_play_count];
                return redirect()->route('point_sail.shortage',$params);
            }

            # ガチャ開始前チェック
            $message =  self::StartCheckMessage( $request, $gacha );
            if( $message ){
                return redirect()->back()
                ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
            }


            # ポイント履歴の登録
            $point_history = self::CreatePointHistory( $total_play_point );

            # ガチャ履歴の登録
            $user_gacha_history = self::CreateGachaHistory( $gacha, $point_history ,$now_play_count );

            # 当たりの選出・ユーザー取得商品の登録・残り商品の減算
            $randReminingGPIdArray = CreateUserPrize::index( $user_gacha_history );


            # ランダムで選出した、ガチャ商品の最大ランク
            $max_rank = self::MaxRank($randReminingGPIdArray );

            # 動画パスの取得
            $movie = self::MoviePath($gacha, $max_rank);
            $user_gacha_history->movie_id = $movie->id;
            $user_gacha_history->save();

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
        if(
            $user->now_rank

            //[会員ランクの利用]config.u_rank_ticketにて設定
            && (bool) config('u_rank_ticket.user_rank',false)

            //[即時ボーナスの有無]config.u_rank_ticketにて設定
            && (bool) config('u_rank_ticket.u_rank_settings.instant_bonuses', true )

        ){
            $rank_up = UserRankHistoryController::CreateRankUpHistory( $user, now(), $user->now_rank );

            ## ランクアップ時のボーナス付与
            if( $rank_up ){
                $desc_first_rank = $user->desc_first_rank;//更新された直近の会員ランク履歴
                UserRankHistoryController::CreateBonusHistory( $user, now(), $desc_first_rank );
            }

        }


        // 二重送信防止
        $request->session()->regenerateToken();



        # スポンサー広告ガチャのとき
        if( $gacha->sponsor_ad ){
            return redirect()->route('gacha.sponsor_ad.movie',compact('user_gacha_history', 'rank_up' ));
        }



        # viewの表示 ($user_gacha_history:ガチャ履歴 )
        $params = $rank_up ? compact('user_gacha_history', 'rank_up' ) : compact('user_gacha_history');
        return redirect()->route('gacha.movie', $params);
        // return redirect()->route('gacha.movie', compact('user_gacha_history', 'movie', 'rank_up' ));
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
        $now_play_count   = (int) $request->play_count;   //プレイ回数
        $play_point       = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
        $total_play_point = $now_play_count*$play_point;//合計使用ポイント
        $remaining_count  = (int) $gacha->remaining_count; //残りのプレイできる回数
        $is_sold_out      = (bool) $gacha->remaining_count < 1; //売り切れかどうか


        // dd($gacha->published_at->toDateTimeString());


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

        # 非公開ガチャの利用不可(非公開または、公開日が現在より先のとき)
        else if(
            !$gacha->published_at || $gacha->published_at->toDateTimeString() > now()->toDateTimeString()
        ){
            return '現在、このガチャを利用することはできません。';
        }
        # [会員ランク限定]
        else if(
            $gacha->dont_auth_user_rank
        ){
            return 'この会員ランクガチャを利用することはできません。';
        }
        # [限定ガチャ]1回or10回限定
        else if(
            $gacha->type=='one_chance' &&  (
                $gacha->played_one_time || ! ($now_play_count==1 || $now_play_count==10)
            )
        ){
            return '現在、このガチャを利用することはできません。';
        }
        # [限定ガチャ]１回限定ガチャ
        else if(
            $gacha->type=='one_time' && (
                $gacha->played_one_time || $now_play_count >1
            )
        ){
            return '現在、このガチャを利用することはできません。';
        }
        # [限定ガチャ]一日一回限定限定ガチャ
        else if(
            $gacha->type=='only_oneday' && (
                $gacha->played_only_oneday || $now_play_count >1
            )
        ){
            return '本日既に、このガチャは利用済みです。';
        }
        # [限定ガチャ] n回限定
        else if(
            in_array( $gacha->type,[ 'n_time', 'n_time_no_custom', ] )
            && ($gacha->type_n_remaining_count < $now_play_count)
        ){
            return '指定の回数は、上限回数をオーバーしています。';
        }
        # [限定ガチャ] 1日n回限定
        else if(
            in_array( $gacha->type,[ 'n_oneday', 'n_oneday_no_custom', ] )
            && ($gacha->type_n_remaining_count < $now_play_count)
        ){
            return '指定の回数は、一日の上限回数をオーバーしています。';
        }


        # [限定ガチャ]新規登録ユーザー定限定ガチャ
        else if(
            $gacha->type=='only_new_user' && (
                Auth::user()->sevendays_affter_registar || $gacha->played_one_time || $now_play_count >1
            )
        ){
            return 'このガチャを利用することはできません。';
        }
        # [時間限定ガチャ]
        else if(!$gacha->is_show_timezone) /*-- (時間帯限定)表示可能か否か --*/
        {
            return '只今このガチャは公開時間外です。';
        }
        # [スポンサー広告ガチャ]1日の上限を超える
        else if($gacha->played_ad_limit)
        {
            return '一日の利用上限を超えました。';
        }

        else{
            return null;
        }
    }



    /**
     * ポイント履歴の登録
     * @param  Integer $total_play_point
     * @return　PointHistory
    */
    public static function CreatePointHistory( $total_play_point )
    {
        $user = Auth::user(); //ログインユーザー取得
        $user->updated_at = now();
        $user->save();//ユーザー情報更新(メール一括送信用)

        $point_history = new PointHistory([
            'user_id'   => $user->id,
            'value'     => - $total_play_point, //使用ポイント数
            'reason_id' => 21 //入出理由ID
        ]);
        $point_history->save();

        return $point_history;
    }


    /**
     * ガチャ履歴の登録
     * @param  Gacha $gacha
     * @param  PointHistory $point_history
     * @param  Integer $now_play_count
     * @return UserGachaHistory
    */
    public static function CreateGachaHistory( $gacha, $point_history ,$now_play_count )
    {
        $user = Auth::user(); //ログインユーザー取得
        $user_gacha_history = new UserGachaHistory([
            'user_id'   => $user->id,  //ユーザー　リレーション
            'gacha_id'  => $gacha->id, //ガチャリレーション
            'point_history_id' => $point_history->id,//ポイント収支履歴リレーション
            'play_count'=> $now_play_count,//ガチャプレイ回数
        ]);
        $user_gacha_history->save();

        return $user_gacha_history;
    }



    /**
     * 最大ランク
     * @param  Array $randReminingGPIdArray
     * @return String
    */
    public static function MaxRank( $randReminingGPIdArray )
    {
        /* 当選商品が複数の場合の演出動画の再生優先順位 */

        # ラストワン・個人賞優先
        $gacha_prizes = GachaPrize::orderBy('gacha_rank_id','asc')//ランクが高い順
        ->whereIn('gacha_rank_id',[ 10,361,362,363 ])
        ->find($randReminingGPIdArray);

        # ゾロ目・キリ番・ポタリ賞・シークレット優先
        $gacha_prizes = $gacha_prizes->count() ? $gacha_prizes :
        GachaPrize::orderBy('gacha_rank_id','asc')//ランクが高い順
        ->whereIn('gacha_rank_id',[ 330,310,320,901,903 ])
        ->find($randReminingGPIdArray);

        # 通常ランク
        $gacha_prizes = $gacha_prizes->count() ? $gacha_prizes :
        GachaPrize::orderBy('gacha_rank_id','asc')//ランクが高い順
        ->find($randReminingGPIdArray);

        return $gacha_prizes[0]->gacha_rank_id;
    }



    /**
     * 動画パスの取得
     * @param  \App\Models\Gacha  $gacha //ガチャモデル
     * @param  String $max _path
     * @param  Array $randReminingGPIdArray //ランダムで選出した、ガチャの商品ID配列
     * @return　Array
    */
    public static function MoviePath( $gacha, $max_rank )
    {


        # ガチャランクごとの演出動画ID
        $id_array = GachaRankMovie::where('gacha_id',$gacha->id)
        ->where('gacha_rank_id', $max_rank)
        ->get()->pluck('movie_id')->toArray();


        # ガチャランクに紐づく動画
        $movies = Movie::find( $id_array );


        # 紐づく動画からランダムで選出
        $movie = $movies->count()>0
            ? $movies[ rand(0, $movies->count()-1 ) ]
            : Movie::inRandomOrder()->first() //指定の動画が無いとき、ランダムな動画を再生
        ;

        return $movie;
    }



}
