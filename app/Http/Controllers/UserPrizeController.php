<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\PointHistory;
use App\Models\GachaCategory;

/*
| =============================================
|  取得した商品一覧 コントローラー
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


    /**
     * 商品のポイント交換
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exchange_points(Request $request)
    {
        # ポイント交換できないとき
        if( config('app.no_exchange_point') )
        {
            return back()->with('alert-warning','商品をポイントに変えることはできません。');
        }

        return back()->with(['alert-warning'=>'商品をポイントに交換しました。']);

    }



    /**
     * 商品のチケット交換
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exchange_tickets(Request $request)
    {
        # チケット交換できないとき
        if( ! config('u_rank_ticket.change_prize_to_ticket') )
        {
            return back()->with('alert-danger','商品をチケットに変えることはできません。');
        }

        return back()->with(['alert-success'=>'商品をチケットに交換しました。']);
    }
}

