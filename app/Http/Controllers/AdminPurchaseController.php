<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminPurchaseRequest;
use App\Models\Purchase;
use App\Models\Prize;
/*
|--------------------------------------------------------------------------
| Admin 買取表　コントローラー
|--------------------------------------------------------------------------
*/
class AdminPurchaseController extends Controller
{
    /**
     * 一覧
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_id = $request->category_id;


        return view('admin.purchase.index',compact(
            'category_id',
        ));
    }




    /**
     * 新規登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        # カテゴリーコードの認証
        $category_id = $request->category_id;

        return view('admin.purchase.create', compact(
            'category_id',
        ));
    }




    /**
     * 登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # 商品ID
        $prize_ids = $request->prize_ids;
        $prizes = Prize::find($prize_ids);


        # 登録
        foreach ($prizes as $prize)
        {
            $purchase = new Purchase([
                'prize_id'    => $prize->id,         //ガチャ用商品リレーション
            ]);
            $purchase->save();
        }


        # 操作ログの更新
        AdminLogController::createLog( 'purchase.create' );

        $request->session()->regenerateToken();// 二重送信防止


        # 返信メッセージ
        return redirect()->route('admin.purchase')
        ->with(['alert-success'=>'ガチャ商品を買取商品として登録しました']);
    }



}
