<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
/*
| =============================================
|  サイト管理者API 登録ユーザー コントローラー
| =============================================
*/
class AdminApiUserController extends Controller
{

    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        # 絞り込み
        $query = User::query();

            $query->with('admin');

            /* その他キーワード検索 */
            self::searchKeysMethod($query, $request );

            if($request->deleted){ //退職者のみ
                $query->where('deleted_at','<>',null);
            }

            $query->withTrashed();//退会者を含む

            $query->orderByDesc('created_at')->orderByDesc('id');

        $users = $query->paginate(20);//ページネーション


        # 追加データ
        foreach ($users as $user)
        {
            $user->image_path       = $user->image_path;
            $user->u_prizes_count   = $user->u_prizes_count;
            $user->gacha_play_count = $user->gacha_play_count;
            $user->point  = $user->point;
            $user->sail   = \App\Models\PointHistory::where('user_id',$user->id)
            ->where('reason_id',11)->get()->sum('price');

            $user->created_at_format        = $user->created_at->format('Y/m/d H:i');        //登録日
            $user->last_access_at_format    = $user->last_access_at->format('Y/m/d H:i');    //最終アクセス
            $user->point_deadline_at_format = $user->point_deadline_at ? $user->point_deadline_text : null ; //ポイント期限

            $user->r_show = route('admin.user.show',$user);

            $user->r_prize  = route('admin.user.user_prize',$user->id);
            $user->r_gacha  = route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>21,]);
            $user->r_sail   = route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>11,]);
            $user->r_point  = route('admin.user.point_history',$user->id);

        }


        # 絞り込みカラム名
        $column_names = [
            'id'=>'ID', 'name'=>'アカウント名', 'email'=>'メールアドレス','twitter_id'=>'X ID',
        ];

        # ルーティング
        $routes = [
            'dl_csv'    => route('admin.user.download_csv'),
            'prize'     => route('admin.user.user_prize',0),
            'gacha'     => route('admin.user.point_history',['user_id'=>0,'reason_id'=>21,]),
            'sail'      => route('admin.user.point_history',['user_id'=>0,'reason_id'=>11,]),
            'point'     => route('admin.user.point_history',0),
        ];
        if(
            config('app.user_prize_deadline_date')   //ユーザー商品期限
            or config('app.user_point_deadline_date')//ポイント機嫌
        ){
            $routes['other_menu'] = route('admin.user.other_menu');
        }


        return response()->json( compact('users','column_names', 'routes') );
    }




        /**
         * キーワード検索メソッド
         * @param Model $query
         * @param Request $request
         * @return JSON
        */
        public static function searchKeysMethod($query, $request )
        {
            # キーワードがなければ、処理をスキップ
            if(!$request->search_keys){return ;}

            $column_name = $request->selected_column_name;

            # 入力キーワードの変換
            $string = str_replace('　',' ', $request->search_keys);//全角空文字の変換
            $search_keys_array = explode(' ',$string);//配列に変換

            // # 処理
            switch ($column_name) {
                /* ID指定のとき */
                case 'id':
                    $query->whereIn('id', $search_keys_array);
                    break;

                /* その他のとき */
                default:
                    foreach( $search_keys_array as $num =>  $search_key )
                    {
                        if($num==0){
                            $query->where(   $column_name, 'like', '%'.$search_key.'%');
                        }else{
                            $query->orWhere( $column_name, 'like', '%'.$search_key.'%');
                        }

                    }
                    break;

                //
            }
        }

}
