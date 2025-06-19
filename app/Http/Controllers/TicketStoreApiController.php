<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\Prize;
use App\Models\UserPrize;
use App\Models\TicketHistory;
use App\Models\GachaCategory;
/*
| =============================================
|  チケット ストアー API コントローラー
| =============================================
*/
class TicketStoreApiController extends Controller
{
    /**
     * 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        # カテゴリー
        $categories = [ new GachaCategory(['name'=>'全て', 'id'=>0]) ];
        $get_categories = GachaCategory::userList()->get();;
        foreach ($get_categories as $get_category) { $categories[] = $get_category; }


        # 販売用チケット情報取得
        $stores = self::Search($request);

        $inputs = $request->all();

        return response()->json( compact('categories','stores','inputs') );
    }




    /**
     * 交換商品情報の取得・検索
     * @return Store $prizes
     */
    public static function Search($request)
    {

        $query = Store::query();

            # 非公開を除く
            $query->where('published_at','<=', now() );

            # カテゴリーの選択
            if(  $request->category_id ){
                $query->where('category_id', $request->category_id);
            }

            #　キーワード検索
            if( $request->key_words ){
                $prize_ids = self::KeyWordPrizeId($request);// キーワードに該当する商品ID($prize_ids)
                $query->whereIn('prize_id', $prize_ids);
            }

            # 並び替え(高チケット順)
            if( $request->order=='desc_ticket_count' ){
                $query->orderByDesc('ticket_count');
            }

            # 並び替え(低チケット順)
            if( $request->order=='asc_ticket_count' ){
                $query->orderBy('ticket_count');
            }

            # 並び替え(公開が古い)
            if( $request->order=='asc_published_at' ){
                $query->orderBy('published_at');
            }else{
                $query->orderByDesc('published_at');
            }

            $query->orderByDesc('created_at')->get();//公開が新しい順

        $stores = $query->with('prize')->paginate(100);


        # 画像パスの登録
        foreach ($stores as $store) {
            $store->prize->image_path = $store->prize->image_path;
            $store->is_published = $store->published_at!=null ;
        }

        return $stores;
    }


    /**
     * 商品IDをキーワード(key_words)から検索
     *
     * @param \Illuminate\Http\Request $req
     * @param App\Models\Recruit::query $query
     * @return App\Models\Recruit::query
     */
    public static function KeyWordPrizeId($request)
    {
        $query = Prize::query();
        AdminApiPrizeController::KeyWordSearch($request, $query);
        $prize_ids = $query->get()->pluck('id')->toArray();

        return $prize_ids;
    }
}
