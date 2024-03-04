<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketSail;

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
        $ticket_sails = TicketSail::where('is_published',1)//公開ずみのみ
        ->orderBy('value','asc')->get();//チケットが低い順

        return view('ticket_sail.index',compact('ticket_sails'));
    }
}
