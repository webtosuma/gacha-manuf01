<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Manuf\AdminGachaTitleRequest;
use App\Models\GachaCategory;
use App\Models\UserRankHistory;
use App\Models\ManufGachaTitle;
use App\Services\Manuf\Admin\GachaTitleService;//サービス
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル コントローラー
| =============================================
*/
class AdminGachaTitleController extends Controller
{
    /** サービスの登録 */
    public function __construct(
        protected GachaTitleService $service
    ){}


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
        if(!$gacha_category&&$category_code){ return abort(404); }//該当なし

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
        $machines = $gacha_title->machines;

        return view('manuf_admin.gacha_title.show', compact(
            'gacha_title','machines',
        ));
    }



    /**
     * 新規作成
     *
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::adminList()->get();

        # 新規作成モデル
        $gacha_title = new ManufGachaTitle();
        if($categories->count()==1)//カテゴリーがひとつの時
        {
            $gacha_title->category_id = $categories[0]->id;
        }


        # ユーザーランク
        $user_ranks = UserRankHistory::UserRanks();


        return view('manuf_admin.gacha_title.create', compact(
            'categories','gacha_title','user_ranks',
        ) );
    }



        /**
         * 登録
         *
         * @param  AdminGachaTitleRequest $request
         * @return \Illuminate\Http\Response
        */
        public function store( AdminGachaTitleRequest $request )
        {
            # 登録サービス
            $gacha_title = $this->service->store($request);

            $request->session()->regenerateToken();// 二重送信防止

            return redirect()
            ->route('admin.gacha_title.show', $gacha_title)
            ->with(['alert-success' => 'ガチャタイトルの基本情報を新規登録しました']);
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
        public function update(
            AdminGachaTitleRequest $request,
            ManufGachaTitle $gacha_title
        ){
            # 更新サービス
            $this->service->update($request, $gacha_title);

            $request->session()->regenerateToken();// 二重送信防止

            return redirect()
            ->route('admin.gacha_title.show', $gacha_title)
            ->with(['alert-warning' => 'ガチャタイトルの基本情報を更新しました']);
        }


    /**
     * 削除
     *
     * @param  Request $request
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,  ManufGachaTitle $gacha_title )
    {
        # DBデータの論理削除
        $this->service->delete($request, $gacha_title);

        $request->session()->regenerateToken();// 二重送信防止

        return redirect()->route('admin.gacha_title')
        ->with(['alert-danger'=>'ガチャタイトルを1件削除しました']);
    }


}
