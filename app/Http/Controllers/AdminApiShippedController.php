<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserShipped;
use App\Models\Prize;
/*
| =============================================
|  Admin API 発送受付 コントローラー 
| =============================================
*/
class AdminApiShippedController extends Controller
{
    /**
     * API 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        # 表示ページ
        $page_count = $request->page_count ?? 20;

        # 購入済み商品(user_shippeds)
        // $user = Auth::user();
        $user_shippeds  = UserShipped::forUserAdmin($request)
        // ->where('user_id',$user->id)
        ->paginate($page_count);


        # 合計数
        $total_count = $request->page ? null
        : UserShipped::forUserAdmin($request)->count();

        # 月データ(初回の読み込み時のみ)
        $months = !$request->page ? self::getMonts($request->state_id) : null;

        # 状態
        $states = UserShipped::state();

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'user_shippeds','total_count','months','states','inputs'
        ) );
    }



        /**
         * 月データリスト（getMonths)
         * @return Array
        */
        public function getMonts($state_id)
        {
            $user = Auth::user();

            return UserShipped::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as format, COUNT(*) as total')
            // ->where('user_id',$user->id)//ログインユーザーのデータのみ
            ->where('state_id',$state_id)//発送待ち・済み
            ->groupBy('format')
            ->orderBy('format', 'desc')
            ->get()
            ->map(function ($item) {
                // 月のフォーマットを「Y年n月」に変換
                $formattedMonth = date('Y年n月', strtotime($item->format . '-01'));
                $date_stanp = date('Y/m/d', strtotime($item->format . '-01'));
                return [
                    'format'     => $formattedMonth.'（'.$item->total.'）',
                    'date_stanp' => $date_stanp,
                    'total'      => $item->total
                ];
            });
        }
}
