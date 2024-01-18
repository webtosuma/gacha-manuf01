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
|  サイト管理者 ユーザー コントローラー
| =============================================
*/
class AdminUserController extends Controller
{
    /**
     * 一覧
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

        $users = $query->orderByDesc('created_at')->get();




        // $users = $search_name
        // ? User::where('name','like','%'.$search_name.'%')->orderByDesc('created_at')->get()//絞り込み
        // : User::orderByDesc('created_at')->get();


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
     * ポイント履歴(個人・全体)
     *
    */
    public function point_history($user_id)
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
        public function point_history_destroy_confirm(Request $request, User $user)
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
       public function point_history_destroy(Request $request, User $user)
       {

           $point_histories = PointHistory::find($request->point_history_ids);

           foreach ($point_histories as $point_history) {
               $point_history->delete();
           }


           return redirect()->route('admin.user.point_history',$user->id)
           ->with(['alert-danger'=>'ポイント履歴を削除しました。']);
       }




    /**
     * ユーザーの取得商品履歴(個人・全体)
     *
    */
    public function user_prize($user_id)
    {
        # ユーザー情報
        $user = $user_id ? User::find($user_id) : null;

        # ユーザーの取得商品情報
        $user_prizes = UserPrize::onlyPossessionScope($user->id)->get();

        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {

            $user_prize->prize->image_path =  $user_prize->prize->image_path;

        }


        return view('admin.user.user_prize', compact('user_prizes','user') );
    }


        /**
         * ユーザーの取得商品削除確認（ユーザー指定）
         *
        */
        public function user_prize_destroy_confirm(Request $request, User $user)
        {
            $created_at = str_replace('T',' ', $request->created_at .':00');

            $user_prizes = UserPrize::onlyPossessionScope($user->id)
            ->where('created_at','>=',$created_at)//指定の日時以降
            ->get();

            return view('admin.user.user_prize_destroy_confirm', compact('user_prizes','user') );
        }


        /**
         * ユーザーの取得商品削除（ユーザー指定）
         *
        */
        public function user_prize_destroy(Request $request, User $user)
        {
            $user_prizes = UserPrize::find($request->user_prize_ids);
            foreach ($user_prizes as $user_prize) {
                $user_prize->delete();
            }


            return redirect()->route('admin.user.user_prize',$user->id)
            ->with(['alert-danger'=>'ユーザーの取得商品を削除しました。']);
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
        $recruiters = User::find($recruiter_ids);

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


            ///test///

            // foreach ($friend_ids as $id) {
            //     $user = User::find($id);

            //     CanpaingIntroductoryController::grant($user);
            // }



        }


        return view('admin.user.canpaing_introductory', compact('recruiters') );
    }

}
