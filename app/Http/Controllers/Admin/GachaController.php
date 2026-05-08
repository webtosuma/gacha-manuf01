<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminLogController;//のちに、サービス化

use Illuminate\Http\Request;
use App\Http\Requests\AdminGachaRequest;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\UserGachaHistory;
use App\Models\UserRankHistory;
use App\Services\Admin\GachaService;
/*
| =============================================
|  Admin ガチャ コントローラー
| =============================================
*/
class GachaController extends Controller
{
    /** サービスの登録 */
    public function __construct(
        protected GachaService $service,
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
        # カテゴリー情報の取得 / カテゴリーコードの確認
        $gacha_category = $this->service->getCategoryCode($category_code);

        return view('admin.gacha.index', compact('gacha_category','category_code'));
    }



    /**
     * 詳細
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function show(Gacha $gacha)
    {
        return view('admin.gacha.show', compact('gacha'));
    }



    /**
     * 新規作成
     *
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function create($category_code=null)
    {
        # カテゴリー情報の取得 / カテゴリーコードの確認
        $gacha_category = $this->service->getCategoryCode($category_code);

        # 新規作成モデル
        $gacha = $this->service->getNewGacha($gacha_category);


        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::adminList()->get();

        # ユーザーランク
        $user_ranks = UserRankHistory::UserRanks();

        # サブスクプラン
        $subscriptions = $this->service->getSubscriptions();


        return view('admin.gacha.create',compact('gacha','categories', 'user_ranks','subscriptions'));
    }



    /**
     * 登録
     *
     * @param  \App\Http\Requests\AdminGachaRequest $request
     * @return \Illuminate\Http\Response
    */
    public function store(AdminGachaRequest $request)
    {
        # 登録サービス
        $gacha = $this->service->store($request);

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.create', $gacha->id );

        $request->session()->regenerateToken();// 二重送信防止


        # 返信メッセージ
        $message = <<<__
        ガチャの基本情報を登録しました
        続けて、次の編集作業を行なってください。
        「登録商品の編集」
        「演出動画の編集」
        「詳細説明の編集」
        「公開設定」
        __;
        return redirect()->route('admin.gacha.prize.edit',$gacha)
        ->with(['alert-primary'=>$message]);
    }



    /**
     * 基本情報　編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::adminList()->get();

        # ユーザーランク
        $user_ranks = UserRankHistory::UserRanks();

        # サブスクプラン
        $subscriptions = $this->service->getSubscriptions();


        return view('admin.gacha.edit', compact('gacha','categories','user_ranks','subscriptions'));
    }



    /**
     * 基本情報　更新
     *
     * @param  \App\Http\Requests\AdminGachaRequest $request
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function update(AdminGachaRequest $request, Gacha $gacha)
    {
        # 更新サービス
        $this->service->update($request, $gacha);

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.edit', $gacha->id );

        $request->session()->regenerateToken();// 二重送信防止

        // return redirect()->route('admin.gacha.show',$gacha)
        return redirect()->route('admin.gacha.edit',$gacha)
        ->with(['alert-warning'=>'ガチャの基本情報を更新しました']);
    }



    /**
     * 公開設定
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function published(Gacha $gacha)
    {
        # カテゴリー内での公開中ガチャ数
        $query = Gacha::query();

            $query->where('category_id',$gacha->category_id);//カテゴリーのみ
            \App\Http\Controllers\GachaApiController::onlyPublished($query);//公開中のみ

        $published_count = $query->count();


        #ガチャの公開制限
        $limit = env('LIMIT_GACHA_COUNT');
        $gacha_restriction = env('LIMIT_GACHA_COUNT') ? $published_count>=$limit : false;
        $gacha_restriction = $gacha->is_published ? false : $gacha_restriction;//公開中のガチャは、公開ボタン制限なし


        return view('admin.gacha.published.edit', compact(
            'gacha', 'gacha_restriction',
        ) );
    }


    /**
     * 公開設定の更新
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function published_update(Request $request, Gacha $gacha)
    {
        # 公開日変数
        $now = now();
        switch ($request['type'])
        {
            # すぐに開始
            case 'start_now':
                $params = [
                    'published_at'    => $now,
                    'end_published_at'=> null,
                ];
                break;

            # すぐに終了
            case 'end_now':
                $params = [
                    'published_at'    => $request['published_at'],
                    'end_published_at'=> $now,
                ];
                break;

            # 予約
            default:
                $params = [
                    'published_at'     => $request['published_at'],
                    'end_published_at' => $request['end_published_at'],
                ];
                break;
            /* */
        }



        # 更新情報の保存
        $gacha->update( $params );

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.published', $gacha->id );

        $request->session()->regenerateToken();// 二重送信防止

        # リダイレクト
        return redirect()->route('admin.gacha.published',$gacha)
        ->with(['alert-warning'=>'ガチャの公開設定を更新しました']);
    }



    /**
     * 削除
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Gacha $gacha)
    {
        # gachaの理論削除
        $this->service->delete( $request, $gacha );

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.delete', $gacha->id );

        $request->session()->regenerateToken();// 二重送信防止

        return redirect()->route('admin.gacha',$gacha->category->code_name)
        ->with(['alert-danger'=>'ガチャを1件削除しました']);
    }



    /**
     * 履歴
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function history(Gacha $gacha)
    {
        $user = null;

        # ポイントの入出理由　絞り込み
        $reason_id = 21;


        # ポイント履歴の取得
        $query = UserGachaHistory::query();

            $query->where('gacha_id', $gacha->id);

            $query->orderByDesc('created_at')->orderByDesc('id');

        $user_gacha_histories = $query->paginate(100);//ページネーション


        return view('admin.gacha.history.index',compact('gacha','user','user_gacha_histories'));
    }



    /**
     * 商品履歴
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function prize_history(Gacha $gacha)
    {
        return view('admin.gacha.prize_history', compact('gacha'));
    }



}
