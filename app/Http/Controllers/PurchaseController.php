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
}
