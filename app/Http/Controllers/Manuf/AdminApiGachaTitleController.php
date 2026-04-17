<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManufGachaTitle;
use App\Models\PrizeRank;
use App\Services\Admin\ApiPrizeService;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル API コントローラー
| =============================================
*/
class AdminApiGachaTitleController extends Controller
{
    /** サービスの登録 */
    protected $ApiPrizeService;
    public function __construct(
        ApiPrizeService $ApiPrizeService
    ){
        $this->ApiPrizeService = $ApiPrizeService;
    }



    /**
     * タイトル商品のprize一覧(筐体口数登録用)
     *
     * @param \Illuminate\Http\Request $request
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function title_prize_prize(
        Request $request,
        ManufGachaTitle $gacha_title
    ){
        # ガチャタイトルの商品(prize)ID配列
        $prize_ids = $gacha_title->title_prizes->pluck('prize_id')->toArray();

        # 一覧
        $per_page = $request->pre_page ?? 20;
        $prizes = $this->ApiPrizeService->getPrizes($request)
        ->whereIn('id', $prize_ids)
        ->paginate($per_page);

        # 商品ランク
        $prize_ranks = PrizeRank::all();//評価ランクデータ

        return response()->json( compact('prizes' ,'prize_ranks') );
    }



}
