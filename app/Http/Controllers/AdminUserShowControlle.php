<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserPrize;
use App\Models\PointHistory;
use App\Models\CanpaingIntroductory;
/*
| =============================================
|  サイト管理者 登録ユーザー詳細 コントローラー
| =============================================
*/
class AdminUserShowControlle extends Controller
{
    /**
     * 登録ユーザー詳細
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index($user)
    {
        $user = User::withTrashed()->find($user);//退会者を含む

        # お友達情報
        $friend_ids = CanpaingIntroductory::where('recruiter_id',$user->id)
        ->pluck('friend_id')->toArray();

        $friends_count = count($friend_ids);

        $friends = User::orderByDesc('created_at')->whereIn('id',$friend_ids)
        ->paginate(20);//ページネーション


        # ポイント購入履歴
        foreach ($friends as $friend) {

            $point_sail_histories = PointHistory::where('user_id',$friend->id)
            ->where('reason_id','11')->get();

            $friend->point_sail_histories = $point_sail_histories;
        }

        // dd($user->id);

        return view('admin.user.show', compact('user','friends','friends_count') );
    }



    /**
     * 登録ユーザーの退会処理
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        // if($user->admin){
        //     return redirect()->back()
        //     ->with(['alert-warning'=>"管理者アカウントを削除することはできません。"]);
        // }

        $user_text = '(id:'.$user->id.')'.$user->name;

        # アカウントの削除
        $user->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'user.delete', $user->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # ログアウト完了ページへリダイレクト
        return redirect()->route('admin.user')
        ->with(['alert-danger'=>$user_text."さんの\nアカウントを削除しました。"]);

    }



    /**
     * 登録ユーザーの退会解除処理
     *
     * @param \Illuminate\Http\Request $request
     * @param $user_id
     * @return \Illuminate\Http\Response
     */
    public function revival(Request $request, $user_id)
    {
        $user = User::withTrashed()->find($user_id);//退会者を含む
        $user->deleted_at = null;
        $user->save();

        # 操作ログの更新
        AdminLogController::createLog( 'user.revival', $user->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        $user_text = '(id:'.$user->id.')'.$user->name;
        return redirect()->route('admin.user.show', $user)
        ->with(['alert-success'=>$user_text."さんの\nアカウント削除を解除しました。"]);
    }

}
