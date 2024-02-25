<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*
| =============================================
|  チケット ストアー コントローラー
| =============================================
*/
class TicketStoreController extends Controller
{
    /**
     * 一覧
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 販売用チケット情報取得
        // $point_sails = PointSail::where('is_published',1)//公開ずみのみ
        // ->orderBy('value','asc')->get();//チケットが低い順
        $ticket_stores = [];

        return view('ticket_store.index',compact('ticket_stores'));
    }
}
