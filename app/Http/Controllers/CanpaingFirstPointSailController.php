<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PointHistory;
use App\Models\CanpaingIntroductory;
/*
| =============================================
|  [キャンペーン]　初回ポイント購入 コントローラー
| =============================================
*/
class CanpaingFirstPointSailController extends Controller
{
    /** 付与するポイント */
    public static function grantPoint(){
        return 1000;
    }


    /** キャンペーンが実施されてるか */
    public static function active(){
        return true;
        // return false;
    }



    ## [初回ポイント購入]ポイント付与
    public static function grant($user)
    {
        $point = self::grantPoint();//付与ポイント

        # ポイント購入数(11)
        $purchases_count = PointHistory::where('user_id',$user->id)
        ->where('reason_id','11')->get()->count();

        # 初回ポイント購入キャンペーン(33)
        $canpaing_reason_id = 33;
        $canpaing_count = PointHistory::where('user_id',$user->id)
        ->where('reason_id',$canpaing_reason_id)->get()->count();


        if(
            self::active()        && // キャンペーンが実施されているか
            // $purchases_count >=1  && // ポイント購入回数が１以上
            $canpaing_count  ==0     // キャンペーン付与履歴なし
        ){
            // 紹介者ポイント付与
            $point_history = new PointHistory([
                'user_id'   => $user->id,          //ユーザー　リレーション
                'value'     => $point, //ポイント数
                'reason_id' => $canpaing_reason_id, //'お友達紹介キャンペーン'
            ]);
            $point_history->save();
        }

    }
}
