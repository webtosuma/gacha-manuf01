<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreKeep;
use App\Models\StoreHistory;
/*
| =============================================
|  EC 発送履歴 コントローラー
| =============================================
*/
class ShippedController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('store.shipped.index');
    }


    /**
     * 詳細
     *
     * @param  String $store_history_code
     * @return \Illuminate\Http\Response
     */
    public function show($store_history_code)
    {
        # 注文履歴
        $user = Auth::user();
        $store_history = StoreHistory::where('user_id',$user->id)
        ->where('code',$store_history_code)->first();
        if( ! $store_history ){ return \App::abort(404); }

        return view('store.shipped.show',compact(
            'store_history'
        ));
    }



    /**
     * API 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        # 購入済み商品(store_histories)
        $user = Auth::user();
        $store_histories  = StoreHistory::forUserAdmin($request)
        ->where('user_id',$user->id)
        ->paginate(10);

        foreach ($store_histories as $store_history) {
            $store_history->sumItemsCount = $store_history->sumItemsCount();  //購入するカート商品の還元ポイント
        }

        # 合計数
        $total_count = $request->page ? null
        : StoreHistory::forUserAdmin($request)
        ->where('user_id',$user->id)
        ->count();


        # 月データ(初回の読み込み時のみ)
        $months = !$request->page ? self::getMonts($request->state_id) : null;

        # 状態
        $states = StoreHistory::states();

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'store_histories','total_count','months','states','inputs'
        ) );
    }



        /**
         * 月データリスト（getMonths)
         * @return Array
        */
        public function getMonts($state_id)
        {
            $user = Auth::user();

            return StoreHistory::selectRaw('DATE_FORMAT(done_at, "%Y-%m") as format, COUNT(*) as total')
            ->where('user_id',$user->id)//ログインユーザーのデータのみ
            ->where('done_at','<>',null)//購入済みの商品のみ
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
