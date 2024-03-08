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
|  Admin チケット ストアー コントローラー
| =============================================
*/
class AdminTicketStoreController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\View\View
    */
    public function index($category_id='')
    {
        return view('admin.ticket_store.index',compact('category_id'));
    }

}
