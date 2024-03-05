<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRankHistory;
/*
| =============================================
|  Admin ユーザー　会員ランク履歴 コントローラー
| =============================================
*/
class AdminUserUserRankHistoryController extends Controller
{
    /**
     * ポイント履歴(個人・全体)
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user(0:全て n:個人)
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, User $user)
    {
        # ポイント履歴の取得
        $query = UserRankHistory::query();

            if($user){
                $query->where('user_id', $user->id);
            }

            $query->orderByDesc('created_at');

        $user_rank_histories = $query->paginate(100);//ページネーション


        return view('admin.user.user_rank_history.index', compact('user_rank_histories', 'user',) );
    }



    /**
     * 会員ランク更新(個人)
     *
     * @param User $user
     * @return \Illuminate\Http\Response
    */
    public function update(User $user)
    {
        # 会員ランクの更新
        UserRankHistoryController::CreateUserRankHistory( $user );

        # リダイレクト
        return redirect()->back()
        ->with(['alert-info'=>$user->name.'さんの会員ランクを更新しました。']);

    }



    /**
     * 会員ランク更新(個人)
     *
     * @return \Illuminate\Http\Response
    */
    public function all_update()
    {
        $users = User::all();

        foreach ($users as $user) {
            # 会員ランクの更新
            UserRankHistoryController::CreateUserRankHistory( $user );
        }

        # リダイレクト
        return redirect()->back()
        ->with(['alert-info'=>'全てのユーザーの会員ランクを更新しました。']);

    }

}
