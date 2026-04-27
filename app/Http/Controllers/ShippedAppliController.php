<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShippedAppliRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserAddress;
use App\Services\ShippedAppliService;
/*
| =============================================
|  発送申請 コントローラー
| =============================================
*/
class ShippedAppliController extends Controller
{
    /** サービスの登録 */
    private $service;
    public function __construct( ShippedAppliService $service)
    {
        $this->service = $service;
    }


    /**
     * 発送申請　入力
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        # 発送商品ID
        $id_array = $request->user_prize_ids;
        if (!$id_array) {
            $message = "発送する商品が選択されていません。";
            return back()->with('alert-warning',$message);
        }


        # ユーザー商品に期限切れがないか、チェック
        $expired_id_array = $this->service->checkDeadline( $id_array );
        if( count( $expired_id_array ) > 0 ){
            $message = "期限切れの商品が含まれています。\n期限切れの商品を発送することはできません。";
            return back()->with('alert-warning',$message);
        }

        # 発送ポイント
        $shipped_point = $this->service->calcShippedPoint( count($id_array) );
        if( $user->point < $shipped_point ){
            $message =  '発送ポイント'. number_format($shipped_point) .'ptが不足しています。';
            return redirect()->route('user_prize')
            ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-triangle']);
        }


        # 発送するユーザー商品を取得/データチェック
        $user_prizes = $this->service->findUserPrizes($id_array);
        if( !$user_prizes->count() ){ return abort(404); }//データがないとき

        # 発送商品の合計ポイント上限
        $limit_prize_point = (Int) config('gacha.shipped.limit_prize_point',0);
        $total_prize_point = 0;
        foreach ($user_prizes as $user_prize) {//カードの重複枚数保存
            $total_prize_point += $user_prize->point;
        }
        if( $total_prize_point < $limit_prize_point ){
            $message =  '発送申請には、合計'. number_format($limit_prize_point) .'pt以上の商品選択が必要です。';
            return redirect()->route('user_prize')
            ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-triangle']);
        }

        # 発送する商品:種類別($shipped_prizes)
        $shipped_prizes = $this->service->buildShippedPrizes($user_prizes);


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
        $ids = $request->user_prize_ids;
        $address_id = $request->user_address_id;

        # 発送ポイント
        $shipped_point = $this->service->calcShippedPoint( count($ids) );
        if( $user->point < $shipped_point ){
            $message =  '発送ポイント'.$shipped_point.'ptが不足しています。';
            return redirect()->route('user_prize')
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);
        }


        # 選択のアドレスをデフォルトにする
        $default_address = (bool) $request->default_address;//選択のアドレスをデフォルトにする
        if( $default_address ){
            UserAddressApiController::UpdateDeffaultAddress( $address_id );
        }
        
        # お届け先アドレス
        $user_address = UserAddress::where('user_id',$user->id)->find($address_id);
        if( !$user_address ){ return abort(404); }//データがないとき

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = $this->service->findUserPrizes($ids);
        if( !$user_prizes->count() ){ return abort(404); }//データがないとき

        # 発送する商品:種類別($shipped_prizes)
        $shipped_prizes = $this->service->buildShippedPrizes($user_prizes);


        return view('shipped.appli.confirm',compact(
            'shipped_point', 'user_address','user_prizes', 'shipped_prizes'
        ) );
    }



    /**
     * 発送申請 完了
     * 
     * @param \App\Http\Requests\Request $request
     * @return \Illuminate\Http\Response
     */
    public function comp(Request $request)
    {
        # 発送申請処理
        $user_shipped = $this->service->executeShipment(
            $request->user_prize_ids,
            $request->user_address_id
        );

        # ユーザーへのメール送信
        $this->service->sendMail($user_shipped);

        $request->session()->regenerateToken(); // 二重投稿防止

        return redirect()->route('shipped.appli.comp.get');

    }
}