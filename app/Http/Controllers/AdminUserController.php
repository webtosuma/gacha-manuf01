<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
        $users = User::doesntHave('admin')//adminユーザーは非表示
        ->orderByDesc('created_at')->get();

        return view('admin.user.index', compact('users') );
    }
}
