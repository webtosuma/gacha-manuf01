<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ManufGachaTitle;
use App\Models\ManufPurchaseHistory;
use App\Models\ManufPurchaseItem;
/*
| =============================================
|  サイト管理者 ページトップ(Home) コントローラー
| =============================================
*/
class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        # 月間売上
        $sales = ManufPurchaseHistory::query()
        ->where('status', 'paid') // 支払い済みのみ
        ->whereYear('paid_at', now()->year)
        ->whereMonth('paid_at', now()->month)
        ->get()
        ->sum(function ($history) {
            return $history->total_fee;
        });

        # 月間販売数
        $purchases_count = ManufPurchaseItem::query()
        ->whereHas('history', function ($query) {
            $query->where('status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month);
        })
        ->sum('count');

        # 登録ユーザー
        $users_count = User::count();

        # 公開中ガチャタイトル
        $published_title_count = ManufGachaTitle::forUser($request)->count();

        # 発送待ち
        $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count();





        return view('manuf_admin.home',compact(
            'sales',
            'purchases_count',
            'users_count',
            'published_title_count',
            'waiting_shippeds_count',
        ));
    }
}
