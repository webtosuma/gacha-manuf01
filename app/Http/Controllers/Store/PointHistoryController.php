<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PointHistory;
/*
| =============================================
|  EC ポイント履歴 コントローラー
| =============================================
*/
class PointHistoryController extends Controller
{
    /**
     * ポイント購入履歴 一覧
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        # ポイント履歴の取得
        $query = PointHistory::query();

            $query->where('user_id', $user->id);

            $query->orderByDesc('created_at')->orderByDesc('id');

        $point_histories = $query->paginate(20);//ページネーション


        # 合計カウント
        $totalPoints = $user->point_histories->sum('value');


        return view('store.point_history.index',compact('point_histories'));
    }
}

