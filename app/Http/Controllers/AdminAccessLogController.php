<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Accesslog;
/*
|--------------------------------------------------------------------------
| Admin アクセスログ コントローラー
|--------------------------------------------------------------------------
*/
class AdminAccessLogController extends Controller
{
    /**
     * ログの起動の有無
     * @return Boolean
    */
    public static function logStartupSetting(){ return true; }


    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd(
        //     Accesslog::forAdmin($request)->get()->toArray()
        // );

        return view('admin.access_log.index');
    }



    /**
     * API・一覧表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function api_list( Request $request )
    {
        # 表示ページ
        $page_count = $request->page_count ?? 20;

        # アクセスログ
        $access_logs = Accesslog::forAdmin($request)
        ->paginate($page_count);

        return response()->json(compact('access_logs'));
    }
}
