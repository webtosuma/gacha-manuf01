<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Store\AdminStoreSalesReportDailyController as Daily;
use App\Http\Controllers\Store\AdminStoreSalesReportController as Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
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
    public function api_products(Request $request)
    {
        # 顧客情報
        $data_list_products = [];

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'data_list_products','inputs'
        ) );
    }


}
