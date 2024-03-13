<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\UserPrize;
use App\Models\TicketHistory;
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
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 販売用チケット情報取得
        $stores = Store::where('published_at','<=', now())//非公開を除く
        ->orderByDesc('published_at')->orderByDesc('created_at')->get();//チケットが低い順
        // dd($stores[0]->prize->toArray());

        return response()->json( compact('stores') );
    }
}
