<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminPointHistoryMonthly as Monthly;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Gacha;
use App\Models\PointHistory;
use App\Models\Admin;
/*
| =============================================
|  ストアーAdmin ページトップ(Home) コントローラー
| =============================================
*/
class AdminHomeController extends Controller
{
    public function index()
    {
        # 登録ユーザー
        $users_count = User::count();

        # 月間売上
        $month = Carbon::parse( now()->format('Y-m-01') );
        $sales = Monthly::Sales($month);

        # 発送待ち
        $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count();

        # お問い合わせ未対応
        $unresponsed_contacts_count = Auth()->user()->admin->unresponsed_contacts->count();

        return view('store_admin.home',compact(
            'users_count','sales','waiting_shippeds_count','unresponsed_contacts_count',
        ));
    }
}
