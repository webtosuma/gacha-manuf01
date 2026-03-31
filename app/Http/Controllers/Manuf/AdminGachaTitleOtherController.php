<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manuf\AdminGachaTitlePublishedRequest;
use Illuminate\Http\Request;
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
    protected GachaTitleService $service;
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
        return view('manuf_admin.gacha_title.movie.edit', compact(
            'gacha_title'
        ) );
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
         * @param \Illuminate\Http\Request $request
         * @param  \App\Models\Gacha  $gacha
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
