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
    public function index(User $user)
    {
        // $price = PointHistory::where('user_id',$user->id)
        // ->where('reason_id',11)->get()->sum('price');
        // dd($price);

        return view('admin.user.show', compact('user') );
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
        $user_text = '(id:'.$user->id.')'.$user->name;

        # アカウントの削除
        $user->delete();
        $request->session()->regenerateToken(); // 二重投稿防止


        # ログアウト完了ページへリダイレクト
        return redirect()->route('admin.user')
        ->with(['alert-danger'=>$user_text."さんの\nアカウントを削除しました。"]);

    }

}
