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

        $query->orderByDesc('published_at')->orderByDesc('created_at');

        return $query;
    }



    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('footer_menu.infomation.index');
    }


    /**
     * 一覧(STORE用)
     *
     * @return \Illuminate\Http\Response
     */
    public function store_index()
    {
        return view('store.infomation.index');
    }


    /**
     * 表示
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function show( Infomation $infomation )
    {
        return view('footer_menu.infomation.show', compact('infomation'));
    }



    /**
     * API・一覧表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function api_list( Request $request )
    {
        # route 新規作成
        $r_create = route('admin.infomation.create');

        # 月別データ
        $months = Infomation::selectRaw('DATE_FORMAT(published_at, "%Y-%m") as format, COUNT(*) as total')
        ->whereNotNull('published_at') // published_at が NULL のデータは除外
        ->where('published_at','<=', now())//公開中のみ
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


        # お知らせの種類
        $new_infomation = new Infomation();
        $types = $new_infomation->is_use_types ? $new_infomation->types : null;


        # お知らせ
        $query = Infomation::query();



            ## 公開月の絞り込み
            if($request->month)
            {
                $startDate = \Carbon\Carbon::parse($request->month)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();

                $query->whereBetween('published_at', [$startDate, $endDate]);
                // $query->where('published_at','<=', now());//公開中
            }

            ## 公開切り替え
            if( $request->published || $request->published==0 )
            {
                switch ($request->published)
                {
                    case 2://予約
                    $query->where('published_at','>', now());
                    break;

                    case 0://未公開
                    $query->where('published_at',null);
                    break;

                    default://公開中
                    $query->where('published_at','<=', now());
                    break;
                }

            }


            # タイトルキーワード
            if( $request->title_keyword )
            {
                $query->where( 'title','like','%'.$request->title_keyword.'%' );
            }


            # お知らせの種類
            if( $request->type )
            {
                $query->where( 'type',$request->type );
            }

            # 非表示にするお知らせの種類(配列)
            if( $request->no_types_array )
            {
                $query->whereNotIn( 'type', $request->no_types_array );
            }

            # 並び順
            $query->orderByDesc('published_at')->orderByDesc('created_at');

        $infomations = $query->paginate(20);

        foreach ($infomations as $infomation)
        {
            if( $request->admin )
            {
                $infomation->r_show       = route('admin.infomation.show',  $infomation);//route 詳細ページ
                $infomation->r_edit       = route('admin.infomation.edit',  $infomation);//route 編集ページ
                $infomation->r_email      = route('admin.infomation.email', $infomation);//route メールページ
                $infomation->r_destroy    = route('admin.infomation.destroy',$infomation);//route 削除ページ
            }else{
                $infomation->r_show       = route('infomation.show',  $infomation);//route 詳細ページ
            }
            $infomation->created_at_format    = $infomation->created_at->format('Y.m.d') ;
            $infomation->published_at_format  = $infomation->published_at? $infomation->published_at->format('Y.m.d') : null ;
            $infomation->send_email_at_format = $infomation->send_email_at? $infomation->send_email_at->format('Y.m.d') : null ;
        }

        return response()->json( compact('r_create','months','infomations','types') );
    }


}
