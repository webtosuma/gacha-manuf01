<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\Prize;
use App\Models\Movie;
use App\Models\GachaRankMovie;

/*
| =============================================
|  サイト管理者 ガチャの演出動画　コントローラー
| =============================================
*/
class AdminGachaMovieController extends Controller
{
    /**
     * 編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        $movies = Movie::all();

        // dd($gacha->discriptions[0]->movies->pluck('id')->toArray());

        return view('admin.gacha.movie.edit', compact('gacha','movies'));
    }



    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gacha $gacha)
    {
        // dd( $request->all() );


        # ランク別詳細情報
        $discriptions = $gacha->discriptions;
        foreach ($discriptions as $discription) {

            $key = 'gri'.$discription->gacha_rank_id.'-movie_ids'; //識別キー gri100

            $old_id_array =
            $discription->movies->pluck('id')->toArray();//更新前、動画ID
            $new_id_array = $request[$key];              //更新後、動画ID

            // dd($discription->gacha_rank_movies->toArray());

            ## ランク動画->新規登録
            foreach ($new_id_array as $id)
            {
                if( !in_array( $id, $old_id_array ) )
                {
                    $gacha_rank_movie = new GachaRankMovie([
                        'gacha_id'      => $gacha->id, //ガチャリレーション
                        'movie_id'      => $id,        //演出動画リレーション
                        'gacha_rank_id' => $discription->gacha_rank_id, //ランクID
                    ]);
                    $gacha_rank_movie->save();
                }
            }


            ## ランク動画->削除
            foreach ($discription->gacha_rank_movies as $gacha_rank_movie)
            {
                if( !in_array( $gacha_rank_movie->movie_id, $new_id_array ) )
                {
                    $gacha_rank_movie->delete();
                }
            }
        }


        # リダイレクト
        return redirect()->route('admin.gacha.movie.edit',$gacha)
        ->with(['alert-warning'=>'ガチャの演出動画を更新しました']);
    }
}
