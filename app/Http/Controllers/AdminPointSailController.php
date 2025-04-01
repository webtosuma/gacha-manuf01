<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminPointSailRequest;
use App\Models\PointSail;
/*
| =============================================
|  サイト管理者 販売ポイント コントローラー
| =============================================
*/
class AdminPointSailController extends Controller
{

    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # 販売用ポイント情報取得
        $point_sails = PointSail::where('is_subscription',false)//サブスク以外
        ->orderByDesc('is_published')//公開中のみ上
        ->orderBy('value','asc')//ポイントが低い順
        ->get();

        return view('admin.point_sail.index',compact('point_sails'));
    }



    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $point_sail = new PointSail([
            'price' => 0,
            'value' => 0,
            'is_published' => 0,
        ]);

        return view('admin.point_sail.create', compact('point_sail'));
    }



    /**
     * 登録
     *
     * @param  \Illuminate\Http\AdminPointSailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminPointSailRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $point_sail = new PointSail( $inputs );
        $point_sail->save();


        # 返信メッセージ
        return redirect()->route('admin.point_sail')
        ->with(['alert-primary'=>'販売ポイントを新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
     */
    public function edit(PointSail $point_sail)
    {
        # サブスク商品の表示不可
        if( $point_sail->is_subscription){ return \App::abort(404); }

        return view('admin.point_sail.edit', compact('point_sail'));
    }



    /**
     * 更新
     *
     * @param  \Illuminate\Http\AdminPointSailRequest  $request
     * @param  \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
     */
    public function update(AdminPointSailRequest $request, PointSail $point_sail)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $point_sail );


        # DBデータの更新
        $point_sail->update( $inputs );


        # リダイレクト
        return redirect()->route('admin.point_sail')
        ->with(['alert-warning'=>'販売ポイント情報を更新しました。']);
    }



    /**
     * 削除
     *
     * @param  \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
     */
    public function destroy( PointSail $point_sail )
    {
        $point_sail->delete();

        # リダイレクト
        return redirect()->route('admin.point_sail')
        ->with(['alert-danger'=>'販売ポイント情報を削除しました。']);
    }



    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PointSail $pointSail //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $pointSail=null )
    {
        $inputs = $request->only(
            'value'       ,// '付与ポイント数',
            'price'       ,// 'ポイント販売価格',
            'is_published',// '公開設定',
            'stripe_id',   //Stipeの商品ID
        );

        # お得分の計算
        $service = $request->value - $request->price;
        $inputs['service'] = $service > 0? $service : 0 ;

        return $inputs;
    }
}
