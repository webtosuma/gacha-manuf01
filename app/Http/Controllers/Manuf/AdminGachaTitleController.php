<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Manuf\AdminGachaTitleRequest;
use App\Models\GachaCategory;
// use App\Models\Gacha;
// use App\Models\GachaDiscription;
// use App\Models\GachaPrize;
// use App\Models\Prize;
// use App\Models\UserGachaHistory;
use App\Models\UserRankHistory;
// use App\Models\PointSail;
use App\Models\ManufGachaTitle;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル コントローラー
| =============================================
*/
class AdminGachaTitleController extends Controller
{
    /**
     * 一覧
     *
     * @param Request $request　
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, $category_code=null )
    {
        # カテゴリーコードの確認
        $category_code = $request->category_code;
        $gacha_category = GachaCategory::where('code_name',$category_code)->first();
        if(!$gacha_category&&$category_code){ return \App::abort(404); }//該当なし

        # タイトル一覧
        $gacha_titles = ManufGachaTitle::orderByDesc('created_at')->get();


        return view('manuf_admin.gacha_title.index', compact(
            'gacha_category','category_code',
            'gacha_titles'
        ));
    }



    /**
     * 詳細
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function show( ManufGachaTitle $gacha_title )
    {
        return view('manuf_admin.gacha_title.show', compact('gacha_title'));
    }


    /**
     * 新規作成
     *
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # 新規作成モデル
        $gacha_title = new ManufGachaTitle();

        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::adminList()->get();

        # ユーザーランク
        $user_ranks = UserRankHistory::UserRanks();


        return view('manuf_admin.gacha_title.create', compact(
            'gacha_title','categories','user_ranks',
        ) );
    }



    /**
     * 登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
    */
    public function store( Request $request)
    {
        // # 入力データの加工
        // $inputs = self::processingInputs( $request );


        // # DBデータの新規登録
        // $gacha_title = new ManufGachaTitle( $inputs, $gacha_title=null );
        // $gacha_title->save();


        // # 操作ログの更新
        // AdminLogController::createLog( 'gacha.create', $gacha_title->id );

        // $request->session()->regenerateToken();// 二重送信防止

            $gacha_title = ManufGachaTitle::first();

        return redirect()->route('admin.gacha_title.show',$gacha_title)
        ->with(['alert-success'=>'ガチャタイトルの基本情報を新規登録しました']);
    }


    /**
     * 基本情報　編集
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function edit( ManufGachaTitle $gacha_title )
    {
        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::adminList()->get();

        # ユーザーランク
        $user_ranks = UserRankHistory::UserRanks();


        return view('manuf_admin.gacha_title.edit', compact(
            'gacha_title','categories','user_ranks',
        ) );
    }


        /**
         * 基本情報　更新
         *
         * @param  AdminGachaTitleRequest $request
         * @param  ManufGachaTitle $gacha_title
         * @return \Illuminate\Http\Response
         */
        public function update( AdminGachaTitleRequest $request, ManufGachaTitle $gacha_title )
        {
            // # 入力データの加工
            // $inputs = self::processingInputs( $request, $gacha_title );

            // # DBデータの更新
            // $gacha->update($inputs);

            // # 操作ログの更新
            // AdminLogController::createLog( 'gacha.edit', $gacha->id );

            // $request->session()->regenerateToken();// 二重送信防止


            return redirect()->route('admin.gacha_title.show',$gacha_title)
            ->with(['alert-warning'=>'ガチャタイトルの基本情報を更新しました']);
        }

}
