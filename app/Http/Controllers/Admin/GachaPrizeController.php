<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Gacha;
use App\Services\Admin\GachaPrizeService;
/*
| =============================================
|  サイト管理者　ガチャの商品 コントローラー
| =============================================
*/
class GachaPrizeController extends Controller
{
    /** サービスの登録 */
    public function __construct(
        protected GachaPrizeService $service,
    ){}
    
    /**
     * 編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        return view('admin.gacha.prize.edit', compact('gacha'));
    }




    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gacha $gacha)
    {
        # 更新サービス
        $this->service->update($request, $gacha);

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.gacha.prize.edit',$gacha)
        ->with(['alert-warning'=>'ガチャの登録商品を更新しました']);
    }


}
