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


        # 公開中ガチャ
        // $gachas = GachaController::getPublishedGachas( $category_code='all' );
        $gachas = Gacha::orderBy('is_sold_out')//売り切れは下
        ->where('published_at', '<=', now())//公開中のみ
        ->has('category')//カテゴリーが存在するもののみ
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->paginate( 20 );//ページネーション

        # 月間売上
        $month = Carbon::parse( now()->format('Y-m-01') );
        $sales = \App\Http\Controllers\AdminPointHistoryMonthly::Sales($month);

        # 発送待ち
        $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count();

        # 月間売上
        $month = Carbon::parse( now()->format('Y-m-01') );
        $store_sales = Monthly::Sales($month);

        # 発送待ち
        $store_waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count();

        return view('store_admin.home',compact(
            'users_count',
            'gachas','sales','waiting_shippeds_count',

            // 'users_count','sales','waiting_shippeds_count','unresponsed_contacts_count',
        ));
    }
}
