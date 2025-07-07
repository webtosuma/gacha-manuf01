<?php

namespace App\Http\Controllers\Store;
use  App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StoreItem;
use App\Models\GachaCategory;
use App\Models\UserStoreKeep;
use App\Models\PointHistory;
use App\Models\StoreSearchHistory;
/*
| =============================================
|  ストアー商品 API コントローラー
| =============================================
*/
class StoreItemApiController extends Controller
{
    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # 交換商品情報の取得・検索
        $store_items = StoreItem::searchQuery($request)//検索スコープ
        ->forUserPublished()//ユーザー公開用スコープ
        ->paginate(20);

        # カテゴリー一覧
        $categories = GachaCategory::where('is_published',1) //公開中
        ->orderBy('created_at')
        ->get();


        #　キーワード検索履歴の保存
        if( $keyword = $request->keyword )
        {
            $user = Auth::user();
            $search_history = StoreSearchHistory::where('user_id',$user->id)
            ->where('keyword',$keyword)->first();
            ## 新規登録
            if( !$search_history )
            {
                $new_search_history = new StoreSearchHistory([
                    'user_id'=> $user->id,
                    'keyword'=> $keyword,
                ]);
                $new_search_history->save();
            }
            ## 更新
            else
            {
                $search_history->update([
                    'count'   => $search_history->count+1,
                    'done_at' => null,
                ]);
            }
        }


        # 並び替え選択肢
        $orders = StoreItem::orders();

        $inputs = $request->all();


        return response()->json( compact(
            'store_items', 'categories','orders', 'inputs'
        ) );
    }



    /**
     * 検索履歴取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search_history(Request $request)
    {
        # 表示数
        $limit =6;

        # 全体の検索履歴
        $query = StoreSearchHistory::select('keyword', DB::raw('SUM(count) as total_count'))
        ->when($request->keyword, function ($q) use ($request) {
            $q->where('keyword', 'like', '%' . $request->keyword . '%');
        })
        ->groupBy('keyword')
        ->orderByDesc('total_count')
        ->limit( $limit );
        $search_histories = $query->get();
        $search_histories = $request->keyword ? $search_histories : [];


        # ユーザーの検索履歴
        $user = Auth::user();
        $user_search_histories = [];
        if($user)
        {
            $query = StoreSearchHistory::query();

                if($request->keyword){
                    $query->where('keyword', 'like', '%' . $request->keyword . '%');
                }

                $query->orderByDesc('count');
                $query->where('user_id',$user->id);//ログインユーザーのみ
                $query->where('done_at',null);//削除されていないデータ

            $user_search_histories = $query->limit( $limit )->get();
        }

        // : StoreSearchHistory::select('keyword', DB::raw('SUM(count) as total_count'))
        // ->when($request->keyword, function ($q) use ($request) {
        //     $q->where('keyword', 'like', '%' . $request->keyword . '%');
        // })
        // ->orderByDesc('total_count')
        // ->groupBy('keyword')
        // ->orderByDesc('total_count')
        // ->where('user_id',$user->id)//
        // ->limit(10)->get();



        return response()->json( compact(
            'search_histories','user_search_histories',
        ) );
    }



    /**
     * 検索履歴削除
     *
     * @param \Illuminate\Http\Request $request
     * @param StoreSearchHistory $search_history
     * @return \Illuminate\Http\Response
     */
    public function search_history_destory( Request $request, StoreSearchHistory $search_history )
    {

        $search_history->update(['done_at' => now()]);

        return response()->json(['message'=>['search_history done OK!']]);
    }



}
