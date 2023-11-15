<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
/*
| =============================================
|  取得した景品一覧 コントローラー
| =============================================
*/
class UserPrizeController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user_prize.index');
    }



    public function exchange_points(Request $request)
    {
        dd($request->all());
    }
}

