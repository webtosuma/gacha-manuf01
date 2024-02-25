<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*
| =============================================
|  チケット販売 コントローラー
| =============================================
*/
class TicketSailController extends Controller
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
        $ticket_sails = [];

        return view('ticket_sail.index',compact('ticket_sails'));
    }
}
