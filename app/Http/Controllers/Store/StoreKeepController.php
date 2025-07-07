<?php

namespace App\Http\Controllers\Store;
use  App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreKeep;
use App\Models\StoreItem;
use App\Models\StoreHistory;

/*
| =============================================
|  EC 買い物カート コントローラー
| =============================================
*/
class StoreKeepController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\View\View
    */
    public function index(Request $request)
    {
        return view('store.store_keep', );
    }

}
