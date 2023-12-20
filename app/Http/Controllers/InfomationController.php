<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Infomation;
use App\Models\InfomationIsRead;
/*
| =============================================
|  お知らせ コントローラー
| =============================================
*/
class InfomationController extends Controller
{
    /** ユーザーページのお知らせモデルを取得 */
    public static function GetInfomationsQuery()
    {

        $query = Infomation::query();

        $query->where('published_at','<=', now()); //非公開を除く


        return $query;
    }



    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # ユーザーページのお知らせモデルを取得
        $infomations = self::GetInfomationsQuery()->get();

        // $user = Auth::user();
        // dd($user->unread_infomation_count);

        return view('footer_menu.infomation.index', compact('infomations'));
    }



    /**
     * 表示
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function show( Infomation $infomation )
    {
        return view('footer_menu.infomation.show', compact('infomation'));
    }
}
