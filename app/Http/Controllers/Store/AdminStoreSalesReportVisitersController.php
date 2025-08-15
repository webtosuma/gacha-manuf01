<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Store\AdminStoreSalesReportDailyController as Daily;
use App\Http\Controllers\Store\AdminStoreSalesReportController as Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\StoreHistory;
/*
| =============================================
|  ストアーAdmin　販売レポート[顧客一覧] コントローラー
| =============================================
*/
class AdminStoreSalesReportVisitersController extends Controller
{
    /**
     * API 顧客一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        // $start_day = Carbon::now()->startOfMonth(); // 今月の1日（00:00:00）
        // $last_day  = Carbon::now()->endOfMonth();   // 今月の末日（23:59:59）

        # 開始日・終了日・日付ラベル
        list( $start_day, $last_day ) = Report::aggDays($request);

        # 顧客情報
        $query = User::query();

            ## 商品購入ユーザーの絞り込み
            $query->whereHas('store_histories', function($sh_query) use ($start_day,$last_day){
                $sh_query->whereNotNull('done_at')
                ->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()]);
            });

            ## 購入回数(sales_count)カラムの追加
            $query->withCount([ 'store_histories as sales_count' => function($sk_query) use ($start_day,$last_day){
                $sk_query->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()])
                ;
            }]);

            ## 購入商品数(product_count)カラムの追加
            $query->withSum([ 'done_store_keeps as product_count' => function($sk_query) use ($start_day,$last_day){
                $sk_query->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()])
                ;
            }],'count');


        $visiters = $query->get();



        # 購入数(payment_count)カラムの追加
        foreach ($visiters as $visiter)
        {
            ## 購入金額(total_price)
            $total_price = 0;
            $store_histories = StoreHistory::where('user_id',$visiter->id)
            ->whereNotNull('done_at')
            ->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()])
            ->get();
            foreach ( $store_histories as $store_history ) {
                $total_price += $store_history->totalItemsPrice();
            }


            $visiter['total_price'] = $total_price;

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
