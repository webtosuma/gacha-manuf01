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
use App\Models\Prize;

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

            '101' => [
                [
                    'pc'     => 'site/movie/pc/101/01.mp4',
                    'mobile' => 'site/movie/mobile/101/01.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/101/02.mp4',
                    'mobile' => 'site/movie/mobile/101/02.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/101/03.mp4',
                    'mobile' => 'site/movie/mobile/101/03.mp4',
                ],

            ],
            '102' => [
                [
                    'pc'     => 'site/movie/pc/102/01.mp4',
                    'mobile' => 'site/movie/mobile/102/01.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/102/01.mp4',
                    'mobile' => 'site/movie/mobile/102/02.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/102/01.mp4',
                    'mobile' => 'site/movie/mobile/102/03.mp4',
                ],
            ],
            '103' => [
                [
                    'pc'     => 'site/movie/pc/103/01.mp4',
                    'mobile' => 'site/movie/mobile/103/01.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/103/01.mp4',
                    'mobile' => 'site/movie/mobile/103/02.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/103/01.mp4',
                    'mobile' => 'site/movie/mobile/103/03.mp4',
                ],
            ],
            '104' => [
                [
                    'pc'     => 'site/movie/pc/104/01.mp4',
                    'mobile' => 'site/movie/mobile/104/01.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/104/02.mp4',
                    'mobile' => 'site/movie/mobile/104/02.mp4',
                ],
                [
                    'pc'     => 'site/movie/pc/104/03.mp4',
                    'mobile' => 'site/movie/mobile/104/03.mp4',
                ],
            ],
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
        // $user_gacha_history = UserGachaHIstory::first();
        // $movie_path = self::MoviePath('XA');
        // return view('gacha.play', compact('user_gacha_history', 'movie_path' ));


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


            # ガチャの残り商品ID配列
            $reminingGPIdArray = self::ReminingGPIdArray( $gacha );
            # ランダムで選出した、ガチャの商品ID配列
            $randReminingGPIdArray = self::RandReminingGPIdArray( $reminingGPIdArray, $play_count);


            #ユーザー取得商品の登録・残り商品の減算
            foreach ($randReminingGPIdArray as $reminingGP_id) {
                self::CreateUserPrize( $user_gacha_history, $reminingGP_id );
            }

            # ランダムで選出した、ガチャ商品の最大ランク
            $max_rank = self::MaxRank($randReminingGPIdArray );

            # 動画パスの取得
            $movie_path = self::MoviePath($max_rank);


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
        # 商品数が、プレイ回数より少ないとき
        else if( $play_count > $remaining_count ){
            return '残りの商品数が少ないため、複数回ガチャをすることができません';
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
     * ガチャの残り商品ID配列
     * @param  Gacha $gacha
     * @return　UserGachaHistory
    */
    public function ReminingGPIdArray( $gacha )
    {
        # ガチャに登録された商品の種類情報
        $gacha->g_prizes;

        //商品の種類数、繰り返し
        $id_array = [];
        foreach ($gacha->g_prizes as $g_prize)
        {
            //商品種類の残り数、繰り返し
            $count = $g_prize->remaining_count;
            for ($i=0; $i < $count; $i++) {

                $id_array[] = $g_prize->id;

            }

        }
        return $id_array;
    }


    /**
     * ランダムで選出した、ガチャの商品ID配列
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
    public function MaxRank( $randReminingGPIdArray )
    {
        $gacha_prizes = GachaPrize::orderBy('gacha_rank_id','asc')//ランクが高い順
        ->find($randReminingGPIdArray);

        return $gacha_prizes[0]->gacha_rank_id;
    }



    /**
     * 動画パスの取得
     * @param  String $max _path
     * @param  Array $randReminingGPIdArray //ランダムで選出した、ガチャの商品ID配列
     * @return　Array
    */
    public function MoviePath( $max_rank )
    {
        $all_movies  = self::Movies();
        $rank_movies = $all_movies[ $max_rank ]; //ランク別画像
        $movies      = $rank_movies[ rand( 0, count($rank_movies)-1 ) ];

        return [
            'pc'     => asset( 'storage/'.$movies['pc'] ),
            'mobile' => asset( 'storage/'.$movies['mobile'] ),
        ];

    }



    /**
     * 一つのユーザー取得商品の登録・残り商品の減算
     * @param  Gacha $gacha
     * @param  Array $randReminingGPIdArray //ランダムで選出した、ガチャの商品ID配列
     * @return　UserPrize
    */
    public function CreateUserPrize( $user_gacha_history, $reminingGP_id )
    {
        $user = Auth::user(); //ログインユーザー取得
        $gacha_prize = GachaPrize::find($reminingGP_id); //ガチャの商品モデル

        # ガチャの商品：残数の更新
        $gacha_prize->remaining_count --;
        $gacha_prize->save();


        # ユーザー取得商品の登録
        $user_prize = new UserPrize([
            'user_id'  => $user->id,  //ユーザー　リレーション
            'prize_id' => $gacha_prize->prize_id,//商品リレーション
            'gacha_history_id'=> $user_gacha_history->id,//主テーブルに関連する従テーブルのレコードを削除
        ]);
        $user_prize->save();
    }


}
