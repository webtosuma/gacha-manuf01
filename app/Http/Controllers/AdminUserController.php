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
|  サイト管理者 登録ユーザー コントローラー
| =============================================
*/
class AdminUserController extends Controller
{
    /**
     * 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # 検索キー
        $search_id    = $request->search_id ? $request->search_id : '';
        $search_name  = $request->search_name ? $request->search_name : '';
        $search_email = $request->search_email ? $request->search_email : '';


        # 絞り込み
        $query = User::query();

            if($search_id){
                $query->where('id','like','%'.$search_id.'%');
            }
            if($search_name){
                $query->where('name','like','%'.$search_name.'%');
            }
            if($search_email){
                $query->where('email','like','%'.$search_email.'%');
            }

        $users = $query->orderByDesc('created_at')->orderByDesc('id')
        ->paginate(100);//ページネーション


        return view('admin.user.index', compact('users','search_id','search_name','search_email') );
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



    /**
     * 紹介者一覧
     *
    */
    public function canpaing_introductory()
    {
        # 紹介書ID
        $recruiter_ids = CanpaingIntroductory::all()->pluck('recruiter_id')->toArray();
        $recruiter_ids = array_unique($recruiter_ids);//重複除去

        # 紹介書
        $recruiters =
        // User::find($recruiter_ids)->paginate(100);
        User::whereIn('id', $recruiter_ids)->paginate(20);
        // dd($recruiters);

        foreach ($recruiters as $recruiter)
        {
            // お友達情報
            $friend_ids = CanpaingIntroductory::where('recruiter_id',$recruiter->id)->pluck('friend_id')->toArray();
            $recruiter->friends = User::find($friend_ids);

            # ポイント購入履歴
            foreach ($recruiter->friends as $friend) {

                $point_sail_histories = PointHistory::where('user_id',$friend->id)
                ->where('reason_id','11')->get();

                $friend->point_sail_histories = $point_sail_histories;
            }

            //
            $recruiter->done_at =  CanpaingIntroductory::where('recruiter_id',$recruiter->id)->first()->done_at;

        }


        return view('admin.user.canpaing_introductory', compact('recruiters') );
    }


}
