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
|  サイト管理者 登録ユーザー [ユーザー商品] コントローラー
| =============================================
*/
class AdminUserUserPrizeController extends Controller
{
    /**
     * ユーザーの取得商品履歴(個人・全体)
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $user_id(0:全て n:個人)
     * @return \Illuminate\Http\Response
    */
    public function index($user_id)
    {
        # ユーザー情報
        $user = $user_id ? User::withTrashed()->find($user_id) : null;//退会者を含む

        # ユーザーの取得商品情報
        $user_prizes = UserPrize::onlyPossessionScope($user_id)
        ->paginate(100);//ページネーション


        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {

            $user_prize->prize->image_path =  $user_prize->prize->image_path;

        }

        return view('admin.user.user_prize.index', compact('user_prizes','user') );
    }



    /**
     * カラム表示
     *
     * @param User $user
     * @return \Illuminate\Http\Response
    */
    public function column(User $user)
    {
        # ユーザーの取得商品情報
        $user_prizes = UserPrize::onlyPossessionScope($user->id)
        ->paginate(100);//ページネーション


        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {

            $user_prize->prize->image_path =  $user_prize->prize->image_path;

        }

        return view('admin.user.user_prize.column', compact('user_prizes','user') );
    }
}
