<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
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
        $users = User::orderByDesc('created_at')->get();

        # 月間売上
        $month = Carbon::parse( now()->format('Y-m-01') );
        $sales = Monthly::Sales($month);

        # 発送待ち
        $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count();

        # お問い合わせ未対応
        $unresponsed_contacts_count = Auth()->user()->admin->unresponsed_contacts->count();

        # 公開中ガチャ
        $gachas = GachaController::getPublishedGachas( $category_code='all' );

        return view('admin.home',compact(
            'users','sales','waiting_shippeds_count','unresponsed_contacts_count','gachas'
        ));
    }
}
