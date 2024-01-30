<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
        $search_id         = $request->search_id ? $request->search_id : '';
        $search_name       = $request->search_name ? $request->search_name : '';
        $search_email      = $request->search_email ? $request->search_email : '';
        $search_twitter_id = $request->search_twitter_id ? $request->search_twitter_id : '';


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
            if($search_twitter_id){
                $query->where('twitter_id','like','%'.$search_twitter_id.'%');
            }

        $users = $query->orderByDesc('created_at')->orderByDesc('id')
        ->paginate(100);//ページネーション


        return view('admin.user.index', compact('users','search_id','search_name','search_email', 'search_twitter_id') );
    }




    /**
     * CSVファイルのダウンロード
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function download_csv(Request $request)
    {
        # 商品情報の取得 (AdminApiPrizeControllerメソッド)
        # 検索キー
        $search_id         = $request->search_id ? $request->search_id : '';
        $search_name       = $request->search_name ? $request->search_name : '';
        $search_email      = $request->search_email ? $request->search_email : '';
        $search_twitter_id = $request->search_twitter_id ? $request->search_twitter_id : '';


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
            if($search_twitter_id){
                $query->where('twitter_id','like','%'.$search_twitter_id.'%');
            }

        $users = $query->orderByDesc('created_at')->orderByDesc('id')
        ->get();


        $data_array = [];
        $header = ['アカウント名','メールアドレス','X(旧Twitter)ID','登録日時'];
        $header = self::convertArrayToSJIS($header);
        $data_array[] = implode(',',$header);

        foreach ($users as $user) {
            $data = [
                $user->name, //カテゴリー名
                $user->email,//商品コード
                $user->twitter_id,
                $user->created_at->format('Y-m-d H:i:s'), //更新日時
            ];

            #UTF-8にエンコード
            $data = self::convertArrayToSJIS($data);

            # カンマに変換
            $data_array[] = implode(',',$data);
        }
        // dd($data_array);


        # 一覧テキストの保存
        $contents = implode("\n",$data_array);     //改行文章に変換し、変数に保存
        $path = 'upload/user/csv/data.csv';//ファイルパス
        Storage::put($path,$contents);

        # 一覧テキストのダウンロード
        return Storage::download($path,'cardFesta登録ユーザー一覧.csv');
    }


        /** UTF-8からSJISにフォーマット */
        public static function convertArrayToSJIS($data)
        {
            array_walk_recursive($data, function (&$value) {
                // $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                $value = mb_convert_encoding($value, 'SJIS', 'UTF-8');
            });

            return $data;
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
