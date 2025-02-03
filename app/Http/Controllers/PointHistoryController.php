<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PointHistory;
/*
| =============================================
|  ポイント購入履歴 コントローラー
| =============================================
*/
class PointHistoryController extends Controller
{

    /** ログイン中のみ処理可能 @return void */
    public function __construct(){ $this->middleware('auth');}

    /**
     * ポイント購入履歴 一覧
     * @param String $month
     * @return \Illuminate\View\View
     */
    public function index( $month='' )
    {
        $user = Auth::user();

        # ポイント履歴の取得
        $query = PointHistory::query();

            $query->where('user_id', $user->id);

            $query->orderByDesc('created_at')->orderByDesc('id');

        $point_histories = $query->paginate(20);//ページネーション


        # 合計カウント
        $totalPoints = $user->point_histories->sum('value');


        return view('point_history.index',compact('point_histories'));
    }

}
