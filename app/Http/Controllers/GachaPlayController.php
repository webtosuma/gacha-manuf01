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
        $play_count = (int) $request->play_count;   //プレイ回数
        $play_point = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
        $total_play_point = $play_count*$play_point;//合計使用ポイント
        $remaining_count  = (int) $gacha->remaining_count; //残りのプレイできる回数
        $is_sold_out = (bool) $gacha->remaining_count < 1; //売り切れかどうか


        # キー認証
        if( $gacha->key!=$key ){ return \App::abort(404); }


        # ポイントが不足しているとき
        if( $total_play_point > $user->point ){
            $params = ['gacha_id'=>$gacha->id];
            return redirect()->route('point_sail.shortage', $params);
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
            $point_history = self::CreatePointHistory( $total_play_point );

            # ガチャ履歴の登録
            $user_gacha_history = self::CreateGachaHistory( $gacha, $point_history ,$play_count );

            # 当たりの選出・ユーザー取得商品の登録・残り商品の減算
            $randReminingGPIdArray = CreateUserPrize::index( $user_gacha_history, $play_count );


            # ランダムで選出した、ガチャ商品の最大ランク
            $max_rank = self::MaxRank($randReminingGPIdArray );

            # 動画パスの取得
            $movie_path = self::MoviePath($gacha, $max_rank);


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
        return view('gacha.play', compact('user_gacha_history', 'movie_path' ));
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
        else if( $play_count > $remaining_count ){
            return '残りの商品数が少ないため、複数回ガチャをすることができません';
        }

        # 非公開ガチャの利用不可(非公開または、公開日が現在より先のとき)
        else if(
            !$gacha->published_at || $gacha->published_at->toDateTimeString() > now()->toDateTimeString()
        ){
            return '現在、このガチャを利用することはできません。';
        }

        # [限定ガチャ]１回限定ガチャ
        else if(
            $gacha->type=='one_time' && $gacha->played_one_time
        ){
            return '現在、このガチャを利用することはできません。';
        }

        # [限定ガチャ]一日一回限定限定ガチャ
        else if(
            $gacha->type=='only_oneday' && $gacha->played_only_oneday
        ){
            return '本日既に、このガチャは利用済みです。';
        }
        # [限定ガチャ]新規登録ユーザー定限定ガチャ
        else if(
            ( $gacha->type=='only_new_user' && Auth::user()->sevendays_affter_registar )or
            ( $gacha->type=='only_new_user' && $gacha->played_one_time )
        ){
            return 'このガチャを利用することはできません。';
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
    public function CreatePointHistory( $total_play_point )
    {
        $user = Auth::user(); //ログインユーザー取得
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
     * @param  Integer $play_count
     * @return UserGachaHistory
    */
    public function CreateGachaHistory( $gacha, $point_history ,$play_count )
    {
        $user = Auth::user(); //ログインユーザー取得
        $user_gacha_history = new UserGachaHistory([
            'user_id'   => $user->id,  //ユーザー　リレーション
            'gacha_id'  => $gacha->id, //ガチャリレーション
            'point_history_id' => $point_history->id,//ポイント収支履歴リレーション
            'play_count'=> $play_count,//ガチャプレイ回数
        ]);
        $user_gacha_history->save();

        return $user_gacha_history;
    }


    /**
     * 最大ランク
     * @param  Array $randReminingGPIdArray
     * @return String
    */
    public function MaxRank( $randReminingGPIdArray )
    {
        $gacha_prizes = GachaPrize::orderBy('gacha_rank_id','asc')//ランクが高い順
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
    public function MoviePath( $gacha, $max_rank )
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

        return  [
            'pc'     => $movie->pc,
            'mobile' => $movie->mobile,
        ];
    }


}
