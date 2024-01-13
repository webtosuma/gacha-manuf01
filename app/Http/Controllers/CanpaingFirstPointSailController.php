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



    ## ポイント付与
    public static function grant($user)
    {
        // $user = Auth::user();
        $point = self::grantPoint();//付与ポイント

        $point_sail_histories = PointHistory::where('user_id',$user->id)
        ->where('reason_id','11')->get();

        if(
            self::active() && // キャンペーンが実施されているか
            $point_sail_histories->count() == 1 // 初回ポイント購入
        ){
            // 紹介者ポイント付与
            $reason_id = 33;//'初回ポイント購入キャンペーン'
            $point_history = new PointHistory([
                'user_id'   => $user->id,          //ユーザー　リレーション
                'value'     => $point, //ポイント数
                'reason_id' => $reason_id, //'お友達紹介キャンペーン'
            ]);
            $point_history->save();
        }

    }
}
