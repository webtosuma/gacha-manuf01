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
        # 顧客情報
        $data_list_visiters = [];

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'data_list_visiters','inputs'
        ) );
    }
}
