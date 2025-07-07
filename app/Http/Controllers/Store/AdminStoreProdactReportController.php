<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/*
| =============================================
|  ストアーAdmin EC売上 コントローラー
| =============================================
*/
class AdminStoreProdactReportController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('store_admin.prodact_report.index');
    }
}
