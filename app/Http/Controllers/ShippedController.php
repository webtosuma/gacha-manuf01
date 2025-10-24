<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserShipped;
use App\Models\Prize;
/*
| =============================================
|  発送 コントローラー
| =============================================
*/
class ShippedController extends Controller
{
    /**
     * 一覧
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $state_id = $request->state_id;
        return view('shipped.index', compact('state_id') );
    }


    /**
     * 詳細
     *
     * @param  \App\Models\UserShipped $user_shipped
     * @return \Illuminate\Http\Response
     */
    public function show(UserShipped $user_shipped)
    {
        $user = Auth::user();
        if( $user_shipped->user_id != $user->id ){ return \App::abort(404); }


        # 既読更新
        if( !$user_shipped->shipment_read ){
            $user_shipped->update(['shipment_read'=>1]);
        }

        # 発送ポイント
        $shipped_point = - (int) $user_shipped->point_history->value;

        # お届け先アドレス
        $user_address = $user_shipped->user_address;

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = $user_shipped->user_prizes;

        # 発送する商品:種類別($shipped_prizes)
        $id_array = $user_prizes->pluck('prize_id')->toArray();
        $shipped_prizes = Prize::withTrashed()->find( $id_array );//カードの重複除去
        foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
            $shipped_prize->count = array_count_values( $id_array )[ $shipped_prize->id ] ?? 0;
        }


        return view('shipped.show',compact(
            'user_shipped','shipped_point','user_address','user_prizes','shipped_prizes'
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
        # 表示ページ
        $page_count = $request->page_count ?? 20;

        # 購入済み商品(user_shippeds)
        $user = Auth::user();
        $user_shippeds  = UserShipped::forUserAdmin($request)
        ->where('user_id',$user->id)
        ->paginate($page_count);


        # 合計数
        $total_count = $request->page ? null
        : UserShipped::forUserAdmin($request)
        ->where('user_id',$user->id)
        ->count();

        # 月データ(初回の読み込み時のみ)
        $months = !$request->page ? self::getMonts($request->state_id) : null;

        # 状態
        $states = UserShipped::state();

        # 入力値
        $inputs = $request->all();

        # 未読数
        $unread_count = $request->page ? null : $user->unread_send_shippeds_count;


        return response()->json( compact(
            'user_shippeds','total_count','months','states','inputs','unread_count',
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
            ->where('user_id',$user->id)//ログインユーザーのデータのみ
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
