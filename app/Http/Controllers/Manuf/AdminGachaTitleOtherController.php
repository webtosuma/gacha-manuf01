<?php

namespace App\Http\Controllers\Manuf;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manuf\AdminGachaTitlePublishedRequest;
use App\Models\Movie;
use App\Models\ManufGachaTitle;
use App\Services\Manuf\GachaTitleService;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル その他の処理 コントローラー
| =============================================
*/
class AdminGachaTitleOtherController extends Controller
{
    /** サービスの登録 */
    protected $service;
    public function __construct(GachaTitleService $service)
    {
        $this->service = $service;
    }



    /**
     * 演出動画情報の編集
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function movie_edit( ManufGachaTitle $gacha_title )
    {
        # タイトル動画一覧
        $title_movies = $gacha_title->title_movies;

        # 演出動画一覧
        $movies = Movie::where('mobile_storage','<>','')->get();

        return view('manuf_admin.gacha_title.movie.edit', compact(
            'gacha_title','title_movies','movies',
        ) );
    }



        /**
         * 演出動画情報の更新
         *
         * @param \Illuminate\Http\Request $request
         * @param  ManufGachaTitle $gacha_title
         * @return \Illuminate\Http\Response
         */
        public function movie_update( 
            Request $request, 
            ManufGachaTitle $gacha_title 
        ){
            # 更新サービス
            $this->service->moviesUpdate( $request, $gacha_title );

            $request->session()->regenerateToken(); // 二重送信防止


            # リダイレクト
            return redirect()
            ->route('admin.gacha_title.show', $gacha_title)
            ->with(['alert-warning' => 'ガチャタイトルの演出動画設定を更新しました']);
        }



    /**
     * 販売・公開期間の編集
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function published_edit( ManufGachaTitle $gacha_title )
    {
        return view('manuf_admin.gacha_title.published.edit', compact(
            'gacha_title'
        ) );
    }

        /**
         * 販売・公開期間の更新
         *
         * @param AdminGachaTitlePublishedRequest $request
         * @param ManufGachaTitle $gacha_title
         * @return \Illuminate\Http\Response
         */
        public function published_update(
            AdminGachaTitlePublishedRequest $request,
            ManufGachaTitle $gacha_title
        ) {
            # 販売・公開期間の更新サービス
            $this->service->publishedUpdate( $request, $gacha_title );

            $request->session()->regenerateToken(); // 二重送信防止


            # リダイレクト
            return redirect()
            ->route('admin.gacha_title.show', $gacha_title)
            ->with(['alert-warning' => 'ガチャタイトルの販売・公開期間を更新しました']);
        }


    /**
     * 履歴
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function history( ManufGachaTitle $gacha_title )
    {
        return view('manuf_admin.gacha_title.history.index', compact(
            'gacha_title'
        ) );
    }



}
