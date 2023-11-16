<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
use App\Models\User;
/*
| =============================================
|  発送申請履歴 コントローラー
| =============================================
*/
class ShippedController extends Controller
{
    /**
     * 発送申請入力
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function appli(Request $request)
    {
        $user = Auth::user();

        # 発送するユーザー景品を取得
        $id_array = $request->user_prize_ids;
        $user_prizes = UserPrize::where('user_id',$user->id) //ユーザー以外のデータを除去
        ->find($id_array);

        return view('shipped.appli',compact('user_prizes'));
    }



    /**
     * 発送申請履歴・発送中
     *
     * @return \Illuminate\Http\Response
     */
    public function current()
    {
        return view('shipped.current');
    }
    /**
     * 発送申請履歴・発送中　詳細 current.show
     *
     * @return \Illuminate\Http\Response
     */
    public function current_show()
    {
        return view('shipped.current_show');
    }

    /**
     * 発送申請履歴・完了済　comp
     *
     * @return \Illuminate\Http\Response
     */
    public function comp()
    {
        return view('shipped.comp');
    }

    /**
     * 発送申請履歴・完了済　詳細
     *
     * @return \Illuminate\Http\Response
     */
    public function comp_show()
    {
        return view('shipped.comp_show');
    }

}
