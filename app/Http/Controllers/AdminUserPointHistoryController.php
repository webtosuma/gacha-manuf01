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
|  サイト管理者 登録ユーザー [ポイント履歴] コントローラー
| =============================================
*/
class AdminUserPointHistoryController extends Controller
{
    /**
     * ポイント履歴(個人・全体)
     *
    */
    public function index($user_id)
    {
        # ユーザー情報
        $user = $user_id ? User::find($user_id) : null;

        $point_histories = $user_id
        ? PointHistory::orderByDesc('created_at')->orderByDesc('id')->where('user_id', $user->id)->get()
        : PointHistory::orderByDesc('created_at')->orderByDesc('id')->get();

        // dd($point_histories->toArray());

        return view('admin.user.point_history', compact('point_histories','user') );
    }



        /**
         * ポイント履歴削除確認（ユーザー指定）
         *
        */
        public function destroy_confirm(Request $request, User $user)
        {
            // dd($request->all());
            $point_history_id = $request->point_history_id;

            $point_histories = PointHistory::orderByDesc('created_at')->orderByDesc('id')->where('user_id', $user->id)
            ->where('id','>=',$point_history_id)//「ポイント購入」を除く
            ->where('reason_id','<>',11)//「ポイント購入」を除く
            ->get();

            return view('admin.user.point_history_destroy_confirm', compact('point_histories','user') );
        }


        /**
        * ポイント履歴削除（ユーザー指定）
        *
       */
       public function destroy(Request $request, User $user)
       {

           $point_histories = PointHistory::find($request->point_history_ids);

           foreach ($point_histories as $point_history) {
               $point_history->delete();
           }


           return redirect()->route('admin.user.point_history',$user->id)
           ->with(['alert-danger'=>'ポイント履歴を削除しました。']);
       }


}
