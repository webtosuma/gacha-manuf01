<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TicketHistory;
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
        $user = Auth::user();

        # 販売用チケット情報取得
        $ticket_histories = TicketHistory::where('user_id',$user->id)
        ->orderByDesc('created_at')
        ->get();

        return view('ticket_history.index',compact('ticket_histories'));
    }
}
