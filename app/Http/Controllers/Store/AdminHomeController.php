<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminPointHistoryMonthly as GachaMonthly;
use App\Http\Controllers\AdminPointHistoryMonthly as StoreMonthly;
use App\Http\Controllers\GachaApiController;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\User;
use App\Models\Gacha;
use App\Models\PointHistory;
use App\Models\StoreItem;
use App\Models\StoreHistory;
/*
| =============================================
|  ストアーAdmin ページトップ(Home) コントローラー
| =============================================
*/
class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        # 登録ユーザー
        $users_count = User::count();


        # ガチャ
            $gacha_query = GachaApiController::getPublishedGachas( '' );
            $month = Carbon::parse( now()->format('Y-m-01') );

            $gacha_data = [

                ## 公開ガチャ数
                'published_count' => $gacha_query->count(),

                ## 月間売上
                'sales' => GachaMonthly::Sales($month),

                ## 発送待ち
                'waiting_shippeds_count' => Auth()->user()->admin->waiting_shippeds->count(),
            ];


        # EC販売
            $start_day = Carbon::now()->startOfMonth(); // 今月の1日（00:00:00）
            $last_day  = Carbon::now()->endOfMonth();   // 今月の末日（23:59:59）

            $store_data = [
                ## 公開ガチャ数
                'published_count' => StoreItem::searchQuery($request)->paginate(20)->count(),

                ## 月間売上
                'sales' => AdminStoreSalesReportController::aggSales( $start_day, $last_day)[0],

                ## 発送待ち
                'waiting_shippeds_count' => StoreHistory::forUserAdmin($request)
                ->where('state_id',11)
                ->count(),
            ];



        return view('store_admin.home',compact(
            'users_count',
            'gacha_data','store_data',
        ));
    }
}
