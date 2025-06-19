<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminGachaRequest;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\Prize;
use App\Models\UserGachaHistory;
use App\Models\UserRankHistory;
use App\Models\PointSail;
/*
| =============================================
|  サイト管理者 ガチャ コントローラー
| =============================================
*/
class AdminGachaController extends Controller
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
        $gacha_category = GachaCategory::where('code_name',$category_code)->first();
        if(!$gacha_category&&$category_code){ return \App::abort(404); }//該当なし

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
        # カテゴリーコードの認証
        $gacha_category = GachaCategory::where('code_name',$category_code)->first();
        if(!$gacha_category&&$category_code){ return \App::abort(404); }//該当なし


        # 新規作成モデル
        $gacha = new Gacha([
            'category_id' => $gacha_category ? $gacha_category->id : null,
            'point'=>0,
            'type' => 'nomal',  //ガチャの種類
            'is_meter'=>1,//残数メーターの表示有無
            'is_slide'=>1,//スライドの表示有無

            'min_time'=>'00:00',// 表示時間下限　2024/04/17追加
            'max_time'=>'24:00',// 表示時間上限　2024/04/17追加
        ]);


        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::adminList()->get();

        # ユーザーランク
        $user_ranks = UserRankHistory::UserRanks();

        # サブスクプラン
        $subscriptions = PointSail::where('is_subscription',true)//サブスクのみ
        ->orderByDesc('is_published')//公開中のみ上
        ->orderByDesc('value')//ポイントが低い順
        ->get();


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
        # 入力データの加工
        $inputs = self::processingInputs( $request );


        # DBデータの新規登録
        $gacha = new \App\Models\Gacha( $inputs, $gacha=null );
        $gacha->save();


        # 詳細情報(discriptions)の登録
        $gacha_ranks = GachaDiscription::gacha_ranks();//ランク情報
        foreach ($gacha_ranks as $gacha_rank_id => $label)
        {
            $gacha_discription = new GachaDiscription([
                'gacha_id'      => $gacha->id, //ガチャリレーション
                'gacha_rank_id' => $gacha_rank_id,//ランクID
            ]);
            $gacha_discription->save();
        }

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
        $subscriptions = PointSail::where('is_subscription',true)//サブスクのみ
        ->orderByDesc('is_published')//公開中のみ上
        ->orderByDesc('value')//ポイントが低い順
        ->get();


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
        # 入力データの加工
        $inputs = self::processingInputs( $request, $gacha );

        # DBデータの更新
        $gacha->update($inputs);

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
        $published_count = Gacha::where('category_id',$gacha->category_id)
        ->where('published_at','<>',null)
        ->get()->count();

        #ガチャの公開制限
        $limit = env('LIMIT_GACHA_COUNT');
        $gacha_restriction = env('LIMIT_GACHA_COUNT') ? $published_count>=$limit : false;
        $gacha_restriction = $gacha->published_at ? false : $gacha_restriction;//公開中のガチャは、公開ボタン制限なし


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
        // dd( $request->all() );
        # 公開日変数

            $published_at = $gacha->published_at;

            // 公開[1](前回が「公開」でないとき)
            if( $request->is_published==1 && !$gacha->is_published ){
                $published_at = now()->format('Y-m-d H:i:s');
            }
            // 公開予約[2]
            else if( $request->is_published==2 ){
                $published_at = str_replace('T',' ', $request->published_at );
            }
            // 非公開[0]
            else if( $request->is_published==0 ){
                $published_at = NULL;
            }


        # 更新情報の保存
        $gacha->update( compact('published_at') );

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
        # 削除用のデータ整理
        $gacha->is_meter = 0;//メーター非表示
        $gacha->is_slide = 0;//スライド非表示
        $gacha->published_at=null;//非公開
        $gacha->save();

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.delete', $gacha->id );

        $request->session()->regenerateToken();// 二重送信防止

        # DBデータの論理削除
        $gacha->delete();

        return redirect()->route('admin.gacha',$gacha->category->code_name)
        ->with(['alert-danger'=>'ガチャを1件削除しました']);
    }


    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gacha $gacha //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $gacha=null )
    {
        $inputs = $request->only(
            'category_id',    //リレーション
            'name',           //名前
            'type',           //ガチャの種類
            'one_play_point', //1回PLAYポイント数

            'image',          //イメージ画像

            'is_meter',       //残数メーターの表示有無
            'is_slide',       //スライドの表示有無
            'user_rank_id',   //会員ランクの指定
            'min_time',       // 表示時間下限　2024/04/17追加
            'max_time',       // 表示時間上限　2024/04/17追加
            'subscription_id',  //サブスクプランID(PointSail) 2025/03/23追加
        );

        # 会員ランク空文字''=>nullに変換
        $inputs['user_rank_id'] = $inputs['user_rank_id']==''? null: $inputs['user_rank_id'];


        # 表示時間の日を跨ぐか否か
        $inputs['is_over_date'] = $inputs['min_time'] > $inputs['max_time'];

        # アクセスキー(新規作成のみ)
        if( $gacha == null ){ $inputs['key'] = \Illuminate\Support\Str::random(16); }


        # ストレージ画像ファイルの更新（イメージ画像）
            $param = 'image';
            $dir = 'upload/gacha/'.$param;                   //保存先ディレクトリ
            $request_file    = $request->file($param);       //画像のリクエスト
            $old_image_path  = $gacha? $gacha->image: null;  //更新前の画像パス
            // $image_dalete    = $request[$param.'_dalete'];      //画像を削除するか否か
            $image_dalete    = null;                         //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;    //コピー用画像パス

            $inputs[$param] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);
        //

        return $inputs;
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
}
