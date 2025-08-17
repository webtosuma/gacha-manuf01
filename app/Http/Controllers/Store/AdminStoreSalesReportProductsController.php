<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Store\AdminStoreSalesReportDailyController as Daily;
use App\Http\Controllers\Store\AdminStoreSalesReportController as Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\StoreItem;
/*
| =============================================
|  ストアーAdmin　販売レポート[商品一覧] コントローラー
| =============================================
*/
class AdminStoreSalesReportProductsController extends Controller
{
    /**
     * API 商品一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        # 開始日・終了日・日付ラベル
        list( $start_day, $last_day ) = Report::aggDays($request);


        # 商品情報
        $query = StoreItem::query();

            ## 商品情報の絞り込み
            $query->whereHas('store_keeps', function($si_query) use ($start_day,$last_day){
                $si_query->whereNotNull('done_at')
                ->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()]);
            });

            ## 合計販売数(sum_count)カラムの追加
            $query->withSum([ 'store_keeps as sum_count' => function($si_query) use ($start_day,$last_day){
                $si_query->whereNotNull('done_at')
                ->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()]);
            }],'count');

            ## 合計売上(sum_price)カラムの追加
            $query->withSum([ 'store_keeps as sum_price' => function($si_query) use ($start_day,$last_day){
                $si_query->whereNotNull('done_at')
                ->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()]);
            }],'done_sum_price');

            ## 合計還元ポイント(sum_points_redemption)カラムの追加
            $query->withSum([ 'store_keeps as sum_points_redemption' => function($si_query) use ($start_day,$last_day){
                $si_query->whereNotNull('done_at')
                ->whereBetween('done_at',[$start_day->startOfDay(),$last_day->endOfDay()]);
            }],'done_sum_points_redemption');


        $products = $query->get();


        # 購入数(payment_count)カラムの追加
        foreach ($products as $product)
        {
            ## [Adminルーティング]ユーザー詳細
            $product['ra_show'] = route('admin.store_item.edit',$product);
        }


        # 並び替え(購入金額が高い順)
        $key = 'sum_count';//購入金額
        $products = $products->toArray();
        usort($products, function ($a, $b) use ($key) {
            // return $a[$key] - $b[$key]; //照準
            return $b[$key] - $a[$key];//降順
        });

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'products','inputs'
        ) );
    }


}
