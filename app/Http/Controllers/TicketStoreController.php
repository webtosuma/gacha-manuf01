<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
/*
| =============================================
|  チケット ストアー コントローラー
| =============================================
*/
class TicketStoreController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 販売用チケット情報取得
        $stores = Store::where('published_at','<=', now())//非公開を除く
        ->orderByDesc('published_at')->orderByDesc('created_at')->get();//チケットが低い順
        // dd($stores[0]->prize->toArray());

        return view('ticket_store.index',compact('stores'));
    }




    /**
     * 詳細
     *
     * @param Store $store
     * @return \Illuminate\View\View
    */
    public function show(Store $store)
    {
        return view('ticket_store.show',compact('store'));
    }



    /**
     * チケット交換処理
     *
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function post( Request $request, Store $store )
    {
        return redirect()->route('ticket_store.comp');
    }


    /**
     * 交換完了
     *
     * @return \Illuminate\View\View
     */
    public function comp()
    {
        $store = Store::first();

        return view('ticket_store.comp',compact('store'));
    }
}
