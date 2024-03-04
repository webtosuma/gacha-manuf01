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
}
