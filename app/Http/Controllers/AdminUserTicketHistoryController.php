<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserPrize;
use App\Models\TicketHistory;
/*
|--------------------------------------------------------------------------
| Admin ユーザー　チケット履歴 コントローラー
|--------------------------------------------------------------------------
*/
class AdminUserTicketHistoryController extends Controller
{
    /**
     * 履歴(個人・全体)
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $user_id(0:全て n:個人)
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $user_id)
    {
        # ユーザー情報
        $user = $user_id ? User::withTrashed()->find($user_id) : null;//退会者を含む

        # 入出理由　絞り込み
        $reason_id = $request->reason_id ?? 0;


        # 履歴の取得
        $query = TicketHistory::query();

            if($user){
                $query->where('user_id', $user->id);
            }
            if($reason_id){
                $query->where('reason_id', $reason_id);
            }

            $query->orderByDesc('created_at')->orderByDesc('id');

        $ticket_histories = $query->paginate(100);//ページネーション




        # ポイントの入出理由　一覧
        $reasons   = TicketHistory::reasons();


        return view('admin.user.ticket_history.index', compact('ticket_histories','user','reasons','reason_id') );
    }
}
