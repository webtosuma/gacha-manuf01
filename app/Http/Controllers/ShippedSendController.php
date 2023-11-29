<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserShipped;
use App\Models\Prize;
/*
| =============================================
|  発送済み コントローラー
| =============================================
*/
class ShippedSendController extends Controller
{
    /** 発送状況ID */
    public function StateId(){
        return 21 ;// '発送済み'
    }


    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # 変数
        $user = Auth::user();
        $state_id = self::StateId();//発送状況ID

        # 発送申請：発送済み
        $shippeds = UserShipped::where('state_id', $state_id)
        ->where('user_id',$user->id)
        ->orderByDesc('shipment_at')->get();

        return view('shipped.send.index', compact('shippeds') );
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

        # お届け先アドレス
        $user_address = $user_shipped->user_address;

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = $user_shipped->user_prizes;

        # 発送する商品:種類別($shipped_prizes)
        $id_array = $user_prizes->pluck('prize_id')->toArray();
        $shipped_prizes = Prize::find( $id_array );//カードの重複除去
        foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
            $shipped_prize->count = array_count_values( $id_array )[ $shipped_prize->id ] ?? 0;
        }

        return view('shipped.send.show', compact('user_address','user_prizes','shipped_prizes') );
    }
}
