<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\GachaCategory;
/*
|--------------------------------------------------------------------------
| 買取表　コントローラー
|--------------------------------------------------------------------------
*/
class PurchaseController extends Controller
{
    /**
     * 一覧
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # 選択されたカテゴリーID
        $category_id = $request->category_id;

        ## ガチャのカテゴリーグループ一覧
        $categories = GachaCategory::where('is_published',1) //公開中
        ->orderBy('created_at')
        ->get();

        return view('purchase.index',compact('category_id','categories'));
    }




    /**
     * 査定
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function appraisal(Request $request)
    {
        # 選択ID
        $ids = implode(',',$request->ids);

        return view('purchase.appraisal', compact('ids'));
    }





    /**
     *  API 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function api(Request $request)
    {
        # 交換商品情報の取得・検索
        $purchases = Purchase::searchQuery($request)
        ->forUserPublished()
        ->paginate(20);


        # カテゴリー一覧
        $categories = GachaCategory::orderBy('created_at')->get();

        # 公開状態選択肢
        $published_statuses = [
            ['key'=>'published',      'label'=> '公開中'  ],
            // ['key'=>'reserv_publish', 'label'=> '公開予約'],
            ['key'=>'an_publish',     'label'=> '未公開'  ],
        ];

        # 並び替え選択肢
        $orders = Purchase::orders();


        $inputs = $request->all();


        return response()->json( compact(
            'purchases','categories', 'published_statuses','orders','inputs'
        ) );
    }




}
