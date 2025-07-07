<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreKeep;
/*
| =============================================
|  EC 購入した商品 コントローラー
| =============================================
*/
class PurchasedController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('store.purchased.index');
    }



    /**
     * API 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        # 購入済み商品(purchaseds)
        $query = StoreKeep::query();


            ## キーワード検索
            $query->keywordSearch($request);

            ## 月の絞り込み
            if($request->month)
            {
                $startDate = \Carbon\Carbon::parse($request->month)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();

                $query->whereBetween('done_at', [$startDate, $endDate]);
            }

            ## 並び替え
            switch ($request->order)
            {
                /*古い順*/
                case 'asc.done_at':
                    $query->orderBy('done_at');
                    break;

                /*新しい順*/
                default:
                    $query->orderByDesc('done_at');
                    break;
            }
            $query->orderByDesc('created_at');


            ## 購入済みの商品のみ
            $query->where('done_at','<>',null);

            ## リレーション
            $query->with('store_item');
            $query->with('store_history');

            ## ログインユーザーのみ
            $user = Auth::user();
            $query->where('user_id',$user->id);

        $purchaseds  = $query->paginate(10);


        # 月データ(初回の読み込み時のみ)
        $months = !$request->page ? self::getMonts() : null;

        # 並び替え
        $orders = [
            ['key'=>'desc.done_at' ,'label'=>'新しい順'],
            ['key'=>'asc.done_at'  ,'label'=>'古い順']
        ];

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'purchaseds','months','orders','inputs'
        ) );
    }



        /**
         * 月データリスト（getMonths)
         * @return Array
        */
        public function getMonts()
        {
            $user = Auth::user();

            return StoreKeep::selectRaw('DATE_FORMAT(done_at, "%Y-%m") as format, COUNT(*) as total')
            ->where('user_id',$user->id)//ログインユーザーのデータのみ
            ->where('done_at','<>',null)//購入済みの商品のみ
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
