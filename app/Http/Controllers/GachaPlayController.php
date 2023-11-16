<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;

/*
| =============================================
|  ガチャ PLAY コントローラー
| =============================================
*/
class GachaPlayController extends Controller
{
    /**
     * 動画ファイルパス
     */
    public function Movies()
    {
        return [
            'XA' => 'site/movie/xa.mp4',
            'XB' => 'site/movie/xb.mp4',
            'XC' => 'site/movie/xc.mp4',
            'XD' => 'site/movie/xd.mp4',

            'MXA' => 'site/movie/mxd.mp4',
            'MXB' => 'site/movie/mxd.mp4',
            'MXC' => 'site/movie/mxd.mp4',
            'MXD' => 'site/movie/mxd.mp4',
        ];
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
        $play_count = (int) $request->play_count;   //プレイ回数
        $play_point = (int) $gacha->one_play_point; //ガチャの1回プレー使用ポイント
        $total_play_point = $play_count*$play_point;//合計使用ポイント
        $remaining_count  = (int) $gacha->remaining_count; //残りのプレイできる回数
        $is_sold_out = (bool) $gacha->remaining_count < 1; //売り切れかどうか


        # キー認証
        if( $gacha->key!=$key ){ return \App::abort(404); }


        # ガチャ開始前チェック
        $message =  self::StartCheckMessage( $request, $gacha );
        if( $message ){
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }



        DB::beginTransaction();
        try {

            # ポイント履歴の保登録
            $point_history = self::CreatePointHistory( $total_play_point );

            # ガチャ履歴の登録
            $user_gacha_history = self::CreateGachaHistory( $gacha, $point_history ,$play_count );


            # ガチャの残り景品ID配列
            $reminingGPIdArray = self::ReminingGPIdArray( $gacha );
            # ランダムで選出した、ガチャの景品ID配列
            $randReminingGPIdArray = self::RandReminingGPIdArray( $reminingGPIdArray, $play_count);


            #ユーザー取得景品の登録・残り景品の減算
            foreach ($randReminingGPIdArray as $reminingGP_id) {
                self::CreateUserPrize( $user_gacha_history, $reminingGP_id );
            }

            # ランダムで選出した、ガチャ景品の最大ランク
            $max_rank = self::MaxRank($randReminingGPIdArray );

            # 動画パスの取得
            $movies = self::Movies();
            $movie_path = [
                'pc'     => asset('storage/'.$movies[ $max_rank ] ),
                'mobile' => asset('storage/'. $movies[ 'M'.$max_rank ] ),
            ];

            DB::commit();


        } catch (\Exception $e) {

            Log::error($e);
            DB::rollback();
            $message = 'エラーが発生しました。';
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);

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

        # ログインしていないとき
        if( !Auth::check() ){
            return 'ガチャを始めるには、ログインが必要です';
        }
        # ポイントが不足しているとき
        else if( $total_play_point > $user->point ){
            return 'ポイントが不足しています';
        }
        # 景品数が、プレイ回数より少ないとき
        else if( $play_count > $remaining_count ){
            return '残りの景品数が少ないため、複数回ガチャをすることができません';
        }
        # 売り切れ
        else if( $is_sold_out ){
            return '売り切れです';
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
     * ガチャの残り景品ID配列
     * @param  Gacha $gacha
     * @return　UserGachaHistory
    */
    public function ReminingGPIdArray( $gacha )
    {
        # ガチャに登録された景品の種類情報
        $gacha->g_prizes;

        //景品の種類数、繰り返し
        $id_array = [];
        foreach ($gacha->g_prizes as $g_prize)
        {
            //景品種類の残り数、繰り返し
            $count = $g_prize->remaining_count;
            for ($i=0; $i < $count; $i++) {

                $id_array[] = $g_prize->id;

            }

        }
        return $id_array;
    }


    /**
     * ランダムで選出した、ガチャの景品ID配列
     * @param  Gacha $gacha
     * @return　UserGachaHistory
    */
    public function RandReminingGPIdArray( $reminingGPIdArray, $play_count)
    {
        // 配列からランダムなキーを選択
        $rand_keys = array_rand( $reminingGPIdArray, $play_count);
        if( is_int($rand_keys) ){ $rand_keys = [$rand_keys ]; }//$play_count==1のとき、返り値を配列にする

        // 抽出されたデータを格納する配列
        $randIdArray = [];
        foreach ($rand_keys as $key) {

            $randIdArray[] = $reminingGPIdArray[$key];
        }
        return $randIdArray;
    }


    /**
     * 最大ランク
     * @param  Array $randReminingGPIdArray
     * @return String
    */
    public function MaxRank( $randReminingGPIdArray ){

        $gacha_prizes = GachaPrize::orderBy('rank_id','asc')//ランクが高い順
        ->find($randReminingGPIdArray);

        return $gacha_prizes[0]->rank_id;
    }



    /**
     * 一つのユーザー取得景品の登録・残り景品の減算
     * @param  Gacha $gacha
     * @param  Array $randReminingGPIdArray //ランダムで選出した、ガチャの景品ID配列
     * @return　UserPrize
    */
    public function CreateUserPrize( $user_gacha_history, $reminingGP_id )
    {
        $user = Auth::user(); //ログインユーザー取得
        $gacha_prize = GachaPrize::find($reminingGP_id); //ガチャの景品モデル

        # ガチャの景品：残数の更新
        $gacha_prize->remaining_count --;
        $gacha_prize->save();
        // dd($gacha_prize);


        # ユーザー取得景品の登録
        $user_prize = new UserPrize([
            'user_id'  => $user->id,  //ユーザー　リレーション
            'prize_id' => $gacha_prize->prize_id,//景品リレーション
            'gacha_history_id'=> $user_gacha_history->id,//主テーブルに関連する従テーブルのレコードを削除
        ]);
        $user_prize->save();
    }

}
