<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * =========================================
 *  サイト管理者　メンテナンス管理　コントローラー
 * =========================================
*/
class AdminMaintenanceController extends Controller
{



    /**
     * 一覧
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        # 編集ページへリダイレクト
        return redirect()->route('admin.maintenance.edit');
    }




    /**
     * 編集
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        # 連想配列データ(Object)をストレージより取得
        $mainteData= Method::getStorageObjData( MaintenanceController::storagePath() );
        $mainteData = $mainteData ?? MaintenanceController::defaultData();//デフォルトデータ
        $mainteData['is_under_mainte'] = MaintenanceController::isUnderMaintenance();//メンテナンス中かいなか
        // $mainteData = MaintenanceController::defaultData();

        return view('admin.maintenance.edit',$mainteData);
    }




    /**
     * 更新(すべて)
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        # 連想配列データ(Object)をストレージより取得
        $mainteData = Method::getStorageObjData( MaintenanceController::storagePath() );
        $mainteData = $mainteData ?? MaintenanceController::defaultData();

        $array =  $request->only(['start_at','end_at','message','show_date']);
        $array['show_date'] = $request->show_date ? 1 : 0;
        Method::putStorageObjData( MaintenanceController::storagePath(),$array);

        # 操作ログの更新
        AdminLogController::createLog( 'maintenance.edit' );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.maintenance.edit')
        ->with(['alert-warning'=>'メンテナンス設定を更新しました。']);
    }
}
