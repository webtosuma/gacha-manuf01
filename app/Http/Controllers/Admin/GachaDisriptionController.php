<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminLogController;//のちに、サービス化

use Illuminate\Http\Request;
use App\Models\Gacha;
use App\Services\StorageService;
use App\Services\Admin\GachaDiscriptionService;
/*
| =============================================
|  Admin ガチャの商品説明 コントローラー
| =============================================
*/
class GachaDisriptionController extends Controller
{
    /** サービスの登録 */
    public function __construct(
        protected GachaDiscriptionService $service,
        protected StorageService $storageService,
    ){}


    /**
     * 編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        return view('admin.gacha.discription.edit', compact('gacha'));
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
        $this->service->update($request,$gacha);

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.discription', $gacha->id );

        $request->session()->regenerateToken();// 二重送信防止


        # リダイレクト
        return redirect()->route('admin.gacha.discription.edit',$gacha)
        ->with(['alert-warning'=>'ガチャの商品説明を更新しました']);
    }

}
