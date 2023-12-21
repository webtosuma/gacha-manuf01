<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointHistory;
/*
| =============================================
|  サイト管理者 ユーザー コントローラー
| =============================================
*/
class AdminUserController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::doesntHave('admin')//adminユーザーは非表示
        // ->orderByDesc('created_at')->get();

        $users = User::orderByDesc('created_at')->get();

        return view('admin.user.index', compact('users') );
    }



    /**
     * ポイント付与
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function add_point(Request $request, User $user)
    {
        // dd( $request->value );

        $point_history = new PointHistory([
            'user_id'   => $user->id,          //ユーザー　リレーション
            'value'     => $request->value, //ポイント数
            'price'     => 0, //販売価格(税込み)
            'reason_id' => 14, // '特別付与'
        ]);
        $point_history->save();

        # リダイレクト
        return redirect()->route('admin.user')
        ->with(['alert-warning'=>$user->name.'さんにポイントを付与しました。']);
    }



}
