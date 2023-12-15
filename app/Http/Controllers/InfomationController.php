<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Infomation;
use App\Models\InfomationIsRead;
/*
| =============================================
|  お知らせ コントローラー
| =============================================
*/
class InfomationController extends Controller
{
    /** ユーザーページのお知らせモデルを取得 */
    public static function GetInfomationsQuery()
    {

        $query = Infomation::query();

            $query->where('published_at','<=', now()); //非公開を除く

            if( Auth::check() ) {
                //他のユーザーのお知らせを除く
                $query->where('user_id',null)->orWhere('user_id',Auth::user()->id);
            }
            else{
                $query->where('user_id',null);
            }

        return $query;
    }



    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # ユーザーページのお知らせモデルを取得
        $infomations = self::GetInfomationsQuery()->get();

        // $user = Auth::user();
        // dd($user->unread_infomation_count);

        return view('footer_menu.infomation.index', compact('infomations'));
    }



    /**
     * 表示
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function show( Infomation $infomation )
    {
        $user = Auth::user();

        # ログインユーザー以外のユーザーのお知らせを表示不可
        if(
            $infomation->user_id  &&  $infomation->user_id != $user->id
        ){ return \App::abort(404); }


        #　既読処理
        if( Auth::check() && !$infomation->is_read ){
            $is_read = new InfomationIsRead([
                'user_id'       => $user->id,
                'infomation_id' => $infomation->id,
            ]);
            $is_read->save();
        }


        return view('footer_menu.infomation.show', compact('infomation'));
    }
}
