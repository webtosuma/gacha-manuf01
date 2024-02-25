<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*
| =============================================
|  チケット 履歴 コントローラー
| =============================================
*/
class TicketHistoryController extends Controller
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
        $ticket_histories = [];

        return view('ticket_history.index',compact('ticket_histories'));
    }
}
