<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminPointSalesReportDailyController as Daily;
use App\Http\Controllers\AdminPointSalesReportController as Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointHistory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\UserGachaHistory;
/*
| =============================================
|  ストアーAdmin　ポイント売上レポート[顧客一覧] コントローラー
| =============================================
*/
class AdminPointSalesReportVisitersController extends Controller
{
    /**
     * API 顧客一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        # 開始日・終了日・日付ラベル
        list( $start_day, $last_day ) = Report::aggDays($request);

        # 顧客情報
        $query = User::query();

            ## 商品購入ユーザーの絞り込み
            $query->whereHas('point_histories', function($sh_query) use ($start_day,$last_day){
                $sh_query->adominPointHistoryReason()
                ->whereBetween('created_at',[$start_day->startOfDay(),$last_day->endOfDay()]);
            });

            ## 購入回数(sales_count)カラムの追加
            $query->withCount([ 'point_histories as sales_count' => function($sh_query) use ($start_day,$last_day){
                $sh_query->adominPointHistoryReason()
                ->whereBetween('created_at',[$start_day->startOfDay(),$last_day->endOfDay()])
                ;
            }]);

        $visiters = $query->get();



        # 購入数(payment_count)カラムの追加
        foreach ($visiters as $visiter)
        {
            ## 購入金額(total_price)
            $visiter['total_price'] = PointHIstory::where('user_id',$visiter->id)
            ->adominPointHistoryReason()//入出ID絞り込み スコープ
            ->whereBetween('created_at',[$start_day->startOfDay(),$last_day->endOfDay()])
            ->sum('price');


            ## [Adminルーティング]ユーザー詳細
            $visiter['ra_user_show'] = route('admin.user.show',$visiter);
        }

        # 並び替え(購入金額が高い順)
        $key = 'total_price';//購入金額
        $visiters = $visiters->toArray();
        usort($visiters, function ($a, $b) use ($key) {
            // return $a[$key] - $b[$key]; //照準
            return $b[$key] - $a[$key];//降順
        });


        # 入力値
        $inputs = $request->all();

        return response()->json( compact(
            'visiters','inputs',
        ) );

    }
}
