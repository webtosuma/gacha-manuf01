<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserShipped;
use App\Models\Prize;

/*
| =============================================
|  サイト管理者 送信済み コントローラー
| =============================================
*/
class AdminShippedSendController extends Controller
{
    /** 発送状況ID */
    public function StateId(){
        return 21 ;// '発送済み'
    }

    /** ページネーション数 */
    public function pagenate_count(){ return 20 ;}

    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # 発送状況ID
        $state_id = self::StateId();

        # 発送申請
        $count = UserShipped::where('state_id', $state_id)->count();
        $count = number_format($count);

        $paginate_shippeds = UserShipped::where('state_id', $state_id)
        ->orderByDesc('shipment_at')
        ->paginate( $this->pagenate_count() );//ページネーション

        return view('admin.shipped.send.index', compact( 'count','paginate_shippeds') );
    }


    /**
     * 詳細
     *
     * @param  \App\Models\UserShipped $user_shipped
     * @return \Illuminate\Http\Response
     */
    public function show( UserShipped $user_shipped )
    {
        # stateが異なれば、非表示
        $state_id = self::StateId();
        if( $user_shipped->state_id != $state_id ){ return \App::abort(404); }

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

        return view('admin.shipped.send.show', compact(
            'user_shipped','shipped_point','user_address','user_prizes','shipped_prizes'
        ) );
    }
}
