<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Store;
use App\Models\UserPrize;
use App\Models\TicketHistory;
/*
| =============================================
|  チケット ストアー コントローラー
| =============================================
*/
class TicketStoreController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 販売用チケット情報取得
        $stores = Store::where('published_at','<=', now())//非公開を除く
        ->orderByDesc('published_at')->orderByDesc('created_at')->get();//チケットが低い順
        // dd($stores[0]->prize->toArray());

        return view('ticket_store.index',compact('stores'));
    }




    /**
     * 詳細
     *
     * @param Store $store
     * @return \Illuminate\View\View
    */
    public function show(Store $store)
    {
        return view('ticket_store.show',compact('store'));
    }



    /**
     * チケット交換処理
     *
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function post( Request $request, Store $store )
    {

        # 変数
        $user = Auth::user();
        $item_count = $request->item_count; //交換商品の数量
        $total_ticket_count = $store->ticket_count * $item_count; //合計チケット利用数

        #＃ 所持チケットが不足
        if( $user->ticket < $total_ticket_count ){
            $message = '所持チケットが不足しています。';
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        #＃ 在庫が不足
        if( $store->count < $item_count ){
            $message = '指定された数量、在庫が存在しません。';
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }


        DB::beginTransaction();
        try {

            # 交換商品(他のリクエストを待機)
            $store = Store::where('id',$store->id)
            ->lockForUpdate()//他のリクエストを待機
            ->first();

            # チケット履歴の保存
            $ticket_history = new TicketHistory([
                'user_id'   => $user->id,          //ユーザー　リレーション
                'value'     => - $total_ticket_count,//チケット利用数
                'reason_id' => 22,// '商品のチケット交換',
                'store_id'  => $store->id
            ]);
            $ticket_history->save();


            # ユーザー商品の登録
            for ($nom =0; $nom  < $item_count; $nom ++)
            {
                $user_prize = new UserPrize([
                    'user_id'    => $user->id,//ユーザー　リレーション
                    'prize_id'   => $store->prize->id,    //商品リレーション
                    'point'      => $store->point_count, //(商品取得時の)交換ポイント値
                    'gacha_history_id' => 1,//入手したガチャのID (仮登録)
                    'ticket_history_id'=> $ticket_history->id,//チケット収支履歴リレーション(チッケトと交換て入手した時のみ)
                ]);
                $user_prize->save();
            }


            # 在庫の減産
            $store->count -= $item_count;
            $store->save();


            DB::commit();


        } catch (\Exception $e) {

            Log::error($e);
            DB::rollback();
            $message = 'エラーが発生しました。';
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);

        }


        // 二重送信防止
        $request->session()->regenerateToken();


        $key = Str::random(10);
        return redirect()->route('ticket_store.comp', compact('ticket_history','key'));
    }

    

    /**
     * 交換完了
     *
     * @param TicketHistory $ticket_history
     * @return \Illuminate\View\View
     */
    public function comp(TicketHistory $ticket_history)
    {
        # ログインユーザー以外は表示できない(401)
        $user = Auth::user();
        if( $ticket_history->user_id != $user->id ){ return \App::abort(401); }


        $store = $ticket_history->store;
        // dd($ticket_history->user_prizes->count());

        return view('ticket_store.comp',compact('store','ticket_history'));
    }
}
