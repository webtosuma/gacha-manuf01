<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prize;
use App\Models\PrizeRank;
use App\Services\Admin\ApiPrizeService;

/*
| =============================================
|  商品情報 サイト管理者API コントローラー
| =============================================
*/
class AdminApiPrizeController extends Controller
{
    /** サービスの登録 */
    protected $service;
    public function __construct(ApiPrizeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # 一覧
        $per_page = $request->pre_page ?? 20;
        $prizes = $this->service->getPrizes($request)
        ->paginate($per_page);

        # その他のデータ
        $prize_ranks = PrizeRank::all();//評価ランクデータ

        return response()->json( compact('prizes' ,'prize_ranks') );
    }





    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Prize;
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prize $prize)
    {
        # 入力データの加工
        $inputs = AdminPrizeController::processingInputs( $request, $prize );

        # DBデータの更新
        $prize->update($inputs);

        # 操作ログの更新
        AdminLogController::createLog( 'prize.edit', $prize->id );


        return response()->json(['prize'=>$prize,'requests'=>$inputs]);
    }




    /**
     * コピー
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Prize;
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request, Prize $prize)
    {
        # 入力データの加工
        // $inputs = AdminPrizeController::processingInputs( $request, $prize );

        # 画像ファイルの複製
        $dir = 'upload/prize/image/';//保存先ディレクトリ
        $path = $prize->image;
        $new_image_path = Method::copyStorageFile( $dir, $path );

        # DBデータのコピー
        $copy_prize = new Prize([
            'category_id' => $prize->category_id,//リレーション
            'code'        => Prize::CreateCode(),//商品コード
            'name'        => $prize->name,       //名前
            'image'       => $new_image_path,    //画像
            'rank_id'     => $prize->rank_id,    //ランクID
            'point'       => $prize->point,      //交換ポイント値
            'point_updated_at' => now(),//交換ポイント値更新日時
            ]);
        $copy_prize->save();

        # 操作ログの更新
        AdminLogController::createLog( 'prize.copy', $copy_prize->id );


        return response()->json(['prize'=>$prize,'copy_prize'=>$copy_prize]);
    }




    /**
     * 削除
     *
     * @param \App\Models\Prize;
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prize $prize)
    {
        # DBデータの理論削除
        $prize->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'prize.delete', $prize->id );

        return response()->json(['message'=>'delete OK!']);
    }



    /**
     * 複数削除
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function multiple_destroy(Request $request)
    {
        # DBデータの理論削除
        $prizes = Prize::find($request->prize_ids);
        foreach( $prizes as $prize) { $prize->delete(); }

        # 操作ログの更新
        AdminLogController::createLog( 'prize.delete' );

        return response()->json(['message'=>'multiple destroy OK!','inputs'=>$request->all()]);
    }


}
