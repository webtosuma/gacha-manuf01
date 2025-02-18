<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * =========================================
 *  メンテナンス管理　コントローラー
 * =========================================
*/
class MaintenanceController extends Controller
{
    /* ストレージ保存パス */
    public static function storagePath(){ return 'upload/maintenance/data.json'; }

    /* デフォルトデータ */
    public static function  defaultData(){
        return [
            'start_at' => '' ,
            'end_at'   => '' ,
            'show_date'=> true ,//時間を表示するか否か
            'message'  => <<<__
            ご不便をおかけしますが、
            ご理解いただけますようよろしくお願いします。
            お時間を置いて、またお越しくださいませ。
            __,
        ];
    }

    /* [データ取得]メンテナンス中か否か */
    public static function  isUnderMaintenance()
    {
        $mainteData= Method::getStorageObjData( self::storagePath() );
        $mainteData = $mainteData ?? self::defaultData();
        $now = now()->format('Y-m-d\TH:i');

        if( !isset($mainteData['start_at']) && !isset($mainteData['end_at']  ) ){ return false;  }//どちらも未入力
        if( !isset($mainteData['start_at']) ){ return $mainteData['end_at']>$now;    }//開始時間が未入力
        if( !isset($mainteData['end_at'])   ){ return $mainteData['start_at']<=$now; }//終了時間が未入力

        return $mainteData['start_at']<=$now && $mainteData['end_at']>$now;
    }




    /**
     * 表示
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        # メンテナンスではないとき
        if ( ! MaintenanceController::isUnderMaintenance()  ) {
            return redirect()->route('home');
        }

        # 連想配列データ(Object)をストレージより取得
        $mainteData= Method::getStorageObjData( MaintenanceController::storagePath() );
        $mainteData = $mainteData ?? MaintenanceController::defaultData();


        return view('maintenance.index',$mainteData);
    }




}
