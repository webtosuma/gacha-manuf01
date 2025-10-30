<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminPurchaseRequest;
use App\Models\Purchase;
use App\Models\PurchaseCategory;
use App\Models\Prize;

use App\Models\GachaCategory;
/*
|--------------------------------------------------------------------------
| Admin 買取表カテゴリー　コントローラー
|--------------------------------------------------------------------------
*/
class AdminPurchaseCategoryController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchase_categories = PurchaseCategory::adminList()->get();

        return view('admin.purchase.category.index', compact('purchase_categories') );
    }


    /**
     * API一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function api_index(Request $request)
    {
        $category = PurchaseCategory::adminList()->get();

        return response()->json( $category );
    }



    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # 新規作成ガチャデータ
        $purchase_category = new PurchaseCategory([
            'is_published' => 0,
        ]);

        return view('admin.purchase.category.create', compact('purchase_category'));
    }



    /**
     * 登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $purchase_category = new PurchaseCategory( $inputs );
        $purchase_category->save();

        # 操作ログの更新
        AdminLogController::createLog( 'purchase.category.create', $purchase_category->id );

        $request->session()->regenerateToken();// 二重送信防止


        # 返信メッセージ
        return redirect()->route('admin.purchase.category')
        ->with(['alert-primary'=>'買取表のカテゴリーを新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\PurchaseCategory $purchase_category
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseCategory $purchase_category)
    {
        return view('admin.purchase.category.edit', compact('purchase_category'));
    }



    /**
     * 更新
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\PurchaseCategory $purchase_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseCategory $purchase_category)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $purchase_category );

        # DBデータの更新
        $purchase_category->update( $inputs );

        # 操作ログの更新
        AdminLogController::createLog( 'purchase_category.edit', $purchase_category->id );

        $request->session()->regenerateToken();// 二重送信防止


        # リダイレクト
        return redirect()->route('admin.purchase.category')
        ->with(['alert-warning'=>'買取表のカテゴリーを更新しました。']);
    }



    /**
     * 削除
     *
     * @param  \App\Models\PurchaseCategory $purchase_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseCategory $purchase_category)
    {

        # カテゴリーの削除
        $purchase_category->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'purchase_category.delete', $purchase_category->id );


        # リダイレクト
        return redirect()->route('admin.purchase.category')
        ->with(['alert-danger'=>'買取表のカテゴリーを削除しました。']);
    }


        /**
         * 入力データの加工 self::processingInputs( $request )
         *
         * @param \Illuminate\Http\Request $request
         * @param \App\Models\Gacha $gacha //新規登録のとき===null
         * @return Array
         */
        public function processingInputs( $request, $purchase_category=null )
        {
            $inputs = $request->only(
                'name',        //名前
                'is_published',//公開(bool)
            );

            return $inputs;
        }



    /**
     * 並び替え
     *
     * @return \Illuminate\Http\Response
     */
    public function change_order()
    {
        return view('admin.purchase.category.change_order');
    }



    /**
     * 並び替え更新
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function change_order_update(Request $request)
    {

        foreach ($request->category_ids as $order => $id)
        {
            $purchase_category = PurchaseCategory::find($id);
            $purchase_category->update(['order'=> $order]);
        }

        # リダイレクト
        return redirect()->route('admin.purchase.category')
        ->with(['alert-warning'=>'買取表のカテゴリーの並び順を更新しました。']);
    }
}
