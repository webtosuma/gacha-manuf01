<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Gacha;
use App\Models\PointHistory;
use App\Models\Admin;

use App\Http\Controllers\AdminPointHistoryMonthly as Monthly;
/*
| =============================================
|  サイト管理者 ページトップ(Home) コントローラー
| =============================================
*/
class AdminHomeController extends Controller
{
    public function index()
    {
        # 公開中ガチャ
        $gachas = GachaApiController::getPublishedGachas( '' )
        ->paginate( 10 );//ページネーション

        # 月間売上
        $month = Carbon::parse( now()->format('Y-m-01') );
        $sales = Monthly::Sales($month);

        # 登録ユーザー
        $users_count = User::count();

        # 発送待ち
        $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count();



        # 管理者アカウントのユーザーID配列
        $admin_user_ids = Admin::pluck('user_id')->toArray();

        # 先月末までのユーザーの合計所持中ポイント
        $sum_point_lastmanth = config('app.admin_top_sum_user_point',false)
        ?PointHistory::where('created_at', '<=', now()->copy()->subMonth()->endOfMonth())
        ->whereNotIn('user_id', $admin_user_ids )//管理者アカウントのポイントを除く
        ->sum('value') : null;

        # 今月のユーザーの合計所持中ポイント
        $sum_point_thismanth = config('app.admin_top_sum_user_point',false)
        ? PointHistory::whereNotIn('user_id', $admin_user_ids )//管理者アカウントのポイントを除く
        ->sum('value') : null;


        return view('admin.home',compact(
            'gachas',
            'sales',
            'users_count',
            'waiting_shippeds_count',
            'sum_point_lastmanth',
            'sum_point_thismanth',
        ));
    }
}
