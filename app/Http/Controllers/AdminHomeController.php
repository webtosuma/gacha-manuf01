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
        # 登録ユーザー
        // $users = User::orderByDesc('created_at')->get();
        // $users_count = User::orderByDesc('id')->first()->id;
        $users_count = User::count();

        # 月間売上
        $month = Carbon::parse( now()->format('Y-m-01') );
        $sales = Monthly::Sales($month);

        # 発送待ち
        $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count();

        # お問い合わせ未対応
        $unresponsed_contacts_count = Auth()->user()->admin->unresponsed_contacts->count();

        # ユーザーの合計所持ポイント
        $admin_user_ids = Admin::all()->pluck('user_id')->toArray();
        $total_user_point = PointHistory::whereNotIn('user_id', $admin_user_ids)->get()->sum('value');


        # 公開中ガチャ
        // $gachas = GachaController::getPublishedGachas( $category_code='all' );
        $gachas = Gacha::orderBy('is_sold_out')//売り切れは下
        ->where('published_at', '<=', now())//公開中のみ
        ->has('category')//カテゴリーが存在するもののみ
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->paginate( 20 );//ページネーション


        return view('admin.home',compact(
            // 'users',
            'users_count',
            'sales','waiting_shippeds_count','unresponsed_contacts_count','gachas','total_user_point'
        ));
    }
}
