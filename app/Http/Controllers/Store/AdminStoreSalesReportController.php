<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/*
| =============================================
|  ストアーAdmin　EC販売商品 コントローラー
| =============================================
*/
class AdminStoreSalesReportController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('store_admin.sales_report.index');
    }
}
