<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShippedAppliRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserPrize;
use App\Models\UserAddress;
use App\Models\PointHistory;
use App\Models\UserShipped;
use App\Models\Prize;
/*
| =============================================
|  発送申請 コントローラー
| =============================================
*/
class ShippedAppliController extends Controller
{
    /** 発送ポイント */
    public function shippedPoint(){ return 100; }




    /**
     * 発送申請　入力
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $id_array = $request->user_prize_ids;//発送するユーザー商品ID

        # 発送ポイント
        $shipped_point = self::shippedPoint();
        // 発送ポイントが足りていないとき
        if( $user->point < $shipped_point ){
            $message =  '発送ポイント'.$shipped_point.'ptが不足しています。';
            return redirect()->route('user_prize')
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = self::FindUserPrizes( $id_array );
        if( !$user_prizes->count() ){ return \App::abort(404); }//データがないとき

        # 発送する商品:種類別($shipped_prizes)
        $sp_id_array = $user_prizes->pluck('prize_id')->toArray();
        $shipped_prizes = Prize::find( $sp_id_array );//カードの重複除去
        foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
            $shipped_prize->count = array_count_values( $sp_id_array )[ $shipped_prize->id ] ?? 0;
        }

        return view('shipped.appli.index',compact(
            'id_array','shipped_point','user_prizes','shipped_prizes'
        ));
    }



    /**
     * 発送申請 確認
     *
     * @param \App\Http\Requests\ShippedAppliRequest $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(ShippedAppliRequest $request)
    {

        $user = Auth::user();
        $id_array = $request->user_prize_ids;//発送するユーザー商品ID
        $default_address_id = $request->user_address_id;//ユーザーアドレスID
        $default_address = (bool) $request->default_address;//選択のアドレスをデフォルトにする
        // dd($request->all());

        # 発送ポイント
        $shipped_point = self::shippedPoint();
        // 発送ポイントが足りていないとき
        if( $user->point < $shipped_point ){
            $message =  '発送ポイント'.$shipped_point.'ptが不足しています。';
            return redirect()->route('user_prize')
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        # 選択のアドレスをデフォルトにする
        if( $default_address ){
            UserAddressApiController::UpdateDeffaultAddress( $default_address_id );
        }

        # お届け先アドレス
        $user_address = UserAddress::where('user_id',$user->id)->find($default_address_id);
        if( !$user_address ){ return \App::abort(404); }//データがないとき

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = self::FindUserPrizes( $id_array );
        if( !$user_prizes->count() ){ return \App::abort(404); }//データがないとき


        # 発送する商品:種類別($shipped_prizes)
        $id_array = $user_prizes->pluck('prize_id')->toArray();
        $shipped_prizes = Prize::find( $id_array );//カードの重複除去
        foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
            $shipped_prize->count = array_count_values( $id_array )[ $shipped_prize->id ] ?? 0;
        }


        return view('shipped.appli.confirm',compact(
            'shipped_point', 'user_address','user_prizes', 'shipped_prizes'
        ) );
    }



    # 発送申請完了comp
    /**
     * 発送申請 完了
     * @param \App\Http\Requests\Request $request
     * @return \Illuminate\Http\Response
     */
    public function comp(Request $request)
    {
        $user = Auth::user();
        $id_array = $request->user_prize_ids;//発送するユーザー商品ID
        $user_address_id = $request->user_address_id;//ユーザーアドレスID
        $default_address = (bool) $request->default_address;//選択のアドレスをデフォルトにする


        # 発送ポイント
        $shipped_point = self::shippedPoint();
        // 発送ポイントが足りていないとき
        if( $user->point < $shipped_point ){
            $message =  '発送ポイント'.$shipped_point.'ptが不足しています。';
            return redirect()->route('user_prize')
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = self::FindUserPrizes( $id_array );
        if( !$user_prizes->count() ){ return \App::abort(404); }//データがないとき


        // dd($user_prizes);
        DB::beginTransaction();
        try {

            # ポイント記録の新規登録
            $point_history = new PointHistory([
                'user_id'   => $user->id, //ユーザー　リレーション
                'value'     =>  - (int) $shipped_point,  //ポイント数
                'reason_id' => 22, //商品発送
            ]);
            $point_history->save();


            # 発送履歴の新規登録
            $user_shipped = new UserShipped([
                'user_id'          => $user->id,         //ユーザー　リレーション
                'user_address_id'  => $user_address_id,  //ユーザーアドレス
                'point_history_id' => $point_history->id,//ポイント収支履歴リレーション
                'state_id'         => 11,//発送準備中
            ]);
            $user_shipped->save();


            # ユーザー商品情報の更新=>発送済み
            foreach ($user_prizes as $user_prize) {
                $user_prize->shipped_id = $user_shipped->id;
                $user_prize->save();
            }


            DB::commit();
            $request->session()->regenerateToken(); // 二重投稿防止
        } catch (\Exception $e) {

            Log::error($e);
            DB::rollback();
            return redirect()->back()->with('alert-warning', 'エラーが発生しました。');

        }


        # ユーザーへのメール送信
        self::SendEmail($user_shipped);


        # Viewの表示
        return redirect()->route('shipped.appli.comp.get');
    }




    /** 発送するユーザー商品を取得 */
    public function FindUserPrizes( $id_array )
    {
        $user = Auth::user();
        $user_prizes = UserPrize::where('user_id',$user->id) //ユーザー以外のデータを除去
        ->where('point_history_id',NULL)//ポイント交換ずみのデータを除く
        ->where('shipped_id',Null)//発送済みデータを除く
        ->orderByDesc('created_at')//取得が新しい順
        ->find( $id_array );

        return $user_prizes;
    }






    /** ユーザーへのメール送信 */
    public function SendEmail($user_shipped)
    {
        # ユーザー情報
        $user = $user_shipped->user;

        # 発送する商品:種類別($shipped_prizes)
        $user_prizes = $user_shipped->user_prizes;
        $id_array = $user_prizes->pluck('prize_id')->toArray();
        $shipped_prizes = Prize::find( $id_array );//カードの重複除去
        foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
            $shipped_prize->count = array_count_values( $id_array )[ $shipped_prize->id ] ?? 0;
        }

        # 変数の保存
        $inputs = compact('user','user_shipped','shipped_prizes');

        // return view('emails.user_shipped_send',$inputs);
        Mail::to( $user->email ) //宛先
        ->send(new \App\Mail\SendHtmlMailMailable([
            'inputs'  => $inputs, //入力変数
            'view'    => 'emails.user_shipped_appli' , //テンプレート
            'subject' => '商品の発送申請を受け付けました', //件名
        ]) );
    }
}
