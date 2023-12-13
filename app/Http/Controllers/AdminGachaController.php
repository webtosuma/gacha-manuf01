<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminGachaRequest;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\Prize;
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
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function index($category_code=null)
    {
        # 表示カテゴリー
        $gacha_category = $category_code
        ? GachaCategory::where('code_name',$category_code)->first()//カテゴリーの指定あり
        : GachaCategory::first();//カテゴリーの指定なし
        if(!$gacha_category){ return \App::abort(404); }//該当なし

        # 表示できるガチャ一覧
        $gachas = $gacha_category->gachas;

        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::all();

        return view('admin.gacha.index', compact('gachas','gacha_category','categories'));
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
    public function create($category_code)
    {
        # 表示カテゴリー
        $gacha_category = GachaCategory::where('code_name',$category_code)->first();
        if(!$gacha_category){ return \App::abort(404); }//該当なし

        # 新規作成モデル
        $gacha = new Gacha([
            'category_id'=>$gacha_category->id,
            'point'=>0,
        ]);

        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::all();

        return view('admin.gacha.create',compact('gacha','categories'));
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
        $categories = GachaCategory::all();

        return view('admin.gacha.edit', compact('gacha','categories'));
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
        return view('admin.gacha.published.edit', compact('gacha'));
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
                $published_at = now()->format('Y-m-d H:i:00');
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


        # リダイレクト
        return redirect()->route('admin.gacha.published',$gacha)
        ->with(['alert-warning'=>'ガチャの公開設定を更新しました']);
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
            'category_id',  //リレーション
            'name',  //名前
            'image', //イメージ画像
            'one_play_point', //1回PLAYポイント数
        );


        # アクセスキー(新規作成のみ)
        if( $gacha == null ){ $inputs['key'] = \Illuminate\Support\Str::random(16); }


        # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/prize/image/';             //保存先ディレクトリ
            $request_file    = $request->file('image');     //画像のリクエスト
            $old_image_path  = $gacha? $gacha->image: null; //更新前の画像パス
            $image_dalete    = $request->image_dalete;      //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;       //コピー用画像パス

            $inputs['image'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);
        //

        return $inputs;
    }
}
