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
|
| =============================================
*/
class AdminUserGachaHistoryController extends Controller
{
    /**
     * 登録ユーザー詳細
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return view('admin.user.show', compact('user') );
    }
}
