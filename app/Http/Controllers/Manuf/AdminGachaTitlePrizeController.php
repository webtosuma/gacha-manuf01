<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Manuf\AdminGachaTitlePrizeRequest;
use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitlePrize;
use App\Models\Prize;
use App\Models\PrizeRank;
use App\Services\Manuf\Admin\GachaTitlePrizeService;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル 商品 コントローラー
| =============================================
*/
class AdminGachaTitlePrizeController extends Controller
{
    /** サービスの登録 */
    public function __construct(
        protected GachaTitlePrizeService $service
    ){}



    /**
     * 一覧
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function index( ManufGachaTitle $gacha_title )
    {
        # タイトル商品 一覧
        $title_prizes = $gacha_title->title_prizes;


        return view('manuf_admin.gacha_title.title_prize.index', compact(
            'gacha_title','title_prizes',
        ));
    }




    /**
     * 新規作成
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function create(ManufGachaTitle $gacha_title)
    {
        # 新規作成モデル
        $title_prize = new ManufGachaTitlePrize  ();
        $title_prize->new_code = Prize::CreateCode();

        # 評価ランクデータ
        $ranks = PrizeRank::all();


        return view('manuf_admin.gacha_title.title_prize.create', compact(
            'gacha_title','title_prize','ranks',
        ) );

    }



        /**
         * 登録
         *
         * @param  AdminGachaTitlePrizeRequest $request
         * @param  ManufGachaTitle $gacha_title
         * @return \Illuminate\Http\Response
        */
        public function store (
            AdminGachaTitlePrizeRequest $request,
            ManufGachaTitle $gacha_title
        ){
            # 登録サービス
            $this->service->store($request, $gacha_title);

            $request->session()->regenerateToken();// 二重送信防止

            return redirect()
            ->route('admin.gacha_title.title_prize', $gacha_title)
            ->with(['alert-success' => 'タイトル商品を新規登録しました']);
        }



    /**
     * 編集
     *
     * @param  ManufGachaTitle $gacha_title
     * @param  ManufGachaTitlePrize $title_prize
     * @return \Illuminate\Http\Response
     */
    public function edit(
        ManufGachaTitle $gacha_title ,
        ManufGachaTitlePrize $title_prize
    ){
        # 評価ランクデータ
        $ranks = PrizeRank::all();


        return view('manuf_admin.gacha_title.title_prize.edit', compact(
            'gacha_title','title_prize','ranks',
        ) );
    }


        /**
         * 更新
         *
         * @param  AdminGachaTitlePrizeRequest $request
         * @param  ManufGachaTitle $gacha_title
         * @param  ManufGachaTitlePrize $title_prize
         * @return \Illuminate\Http\Response
         */
        public function update(
            AdminGachaTitlePrizeRequest $request,
            ManufGachaTitle $gacha_title,
            ManufGachaTitlePrize $title_prize
        ){
            # 更新サービス
            $this->service->update($request, $title_prize);

            $request->session()->regenerateToken();// 二重送信防止

            return redirect()
            ->route('admin.gacha_title.title_prize', $gacha_title)
            ->with(['alert-warning' => 'タイトル商品を更新しました']);
        }


    /**
     * 削除
     *
     * @param  Request $request
     * @param  ManufGachaTitle $gacha_title
     * @param  ManufGachaTitlePrize $title_prize
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        ManufGachaTitle $gacha_title,
        ManufGachaTitlePrize $title_prize,
    ){
        # DBデータの論理削除
        $this->service->delete($request, $title_prize);

        $request->session()->regenerateToken();// 二重送信防止

        return redirect()
        ->route('admin.gacha_title.title_prize', $gacha_title)
        ->with(['alert-danger'=>'タイトル商品を1件削除しました']);
    }



    /**
     * コピー
     *
     * @param  Request $request
     * @param  ManufGachaTitle $gacha_title
     * @param  ManufGachaTitlePrize $title_prize
     * @return \Illuminate\Http\Response
     */
    public function copy(
        Request $request,
        ManufGachaTitle $gacha_title,
        ManufGachaTitlePrize $title_prize
    ){
        $this->service->copy($title_prize, $gacha_title);

        $request->session()->regenerateToken();

        return redirect()
        ->route('admin.gacha_title.title_prize', $gacha_title)
        ->with(['alert-warning' => 'タイトル商品をコピーしました']);
    }


}
