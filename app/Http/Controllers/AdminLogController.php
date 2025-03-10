<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Admin;
use App\Models\AdminLog;
/*
| =============================================
|  サイト管理者　操作履歴 コントローラー
| =============================================
*/
class AdminLogController extends Controller
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
        $log = AdminLog::orderByDesc('id')->first();
        // dd($log->movie);

        return view('admin.log.index');
    }



    /**
     * API・一覧表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function api_list( Request $request )
    {
        # データの削除
        self::apiDestory($request);

        # 月別データ
        $months = self:: getApiMonth();

        # 操作履歴
        $logs = self:: getApiLogs($request);

        # サイト管理者
        $query = Admin::query();
        // if( ! auth()->user()->admin->fobees ){
        //     //fobeesアカウントを含まない
        //     $user_ids = User::whereIn('email',config('app.fobees_emails'))->get()->pluck('id')->toArray();
        //     $query->whereNotIn('user_id',$user_ids);
        // }
        $admins = $query->with('user')->get();

        // $admins = Admin::with('user')->get();

        # 履歴の種類
        $types = AdminLog::types();


        return response()->json( compact('months','logs','admins','types') );
    }

        /** データの削除 */
        public function apiDestory($request)
        {
            if(!$request->destory){ return; }

            $logs = AdminLog::whereIn('id',$request->log_ids)->get();
            foreach ($logs as $log) {
                $log->delete();
            }
        }


        /** 月データ */
        public function getApiMonth()
        {
            return AdminLog::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as format, COUNT(*) as total')
            ->whereNotNull('created_at') // created_at が NULL のデータは除外
            ->where('created_at','<=', now())//公開中のみ
            ->groupBy('format')
            ->orderBy('format', 'desc')
            ->get()
            ->map(function ($item) {
                // 月のフォーマットを「Y年n月」に変換
                $formattedMonth = date('Y年n月', strtotime($item->format . '-01'));
                $date_stanp = date('Y/m/d', strtotime($item->format . '-01'));
                return [
                    'format'     => $formattedMonth.'（'.$item->total.'）',
                    'date_stanp' => $date_stanp,
                    'total'      => $item->total
                ];
            });

        }


        /** 操作履歴データ */
        public function getApiLogs($request)
        {
            # データ抽出
            $query = AdminLog::query();

                ## fobeesアカウントでない場合、fobeesアカウントの履歴を含まない
                // if( ! auth()->user()->admin->fobees ){
                //     $user_ids  = User::whereIn('email',config('app.fobees_emails'))->get()->pluck('id')->toArray();
                //     $admin_ids = Admin::whereIn('user_id',$user_ids)->get()->pluck('id')->toArray();
                //     $query->whereNotIn('admin_id',$admin_ids);
                // }

                ## 月の絞り込み
                if($request->month)
                {
                    $startDate = \Carbon\Carbon::parse($request->month)->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();

                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                ## サイト管理者の絞り込み
                if($request->admin_id)
                {
                    $query->where('admin_id', $request->admin_id);
                }
                ## 履歴の種類の絞り込み
                if($request->type_id)
                {
                    $query->where('type_id', $request->type_id);
                }


                $query->orderByDesc('created_at');//並び順
                $query->whereIn('type_id',AdminLog::typeIdArray()); //指定されてるIDのみ抽出
                $query->with('admin');//リレーション

            $logs = $query->paginate(20);



            # 追加データ
            foreach ($logs as $log) {

            }


            # データを返す
            return $logs;
        }



        /**
         * 履歴登録メソッド(外部コントローラー)
         *
         * @param String  $type_id
         * @param Integer $target_id
         * @return Void
         */
        public static function createLog($type_id,$target_id=null)
        {
            # ログの起動の有無
            if( ! self::logStartupSetting() ){ return; }

            $admin_log = new AdminLog([
                'admin_id'  => auth()->user()->admin->id,// リレーションID
                'type_id'   => $type_id,   // 履歴の種類
                'target_id' => $target_id, // 履歴に関係するデータの紐付け
            ]);
            $admin_log->save();
        }

}
