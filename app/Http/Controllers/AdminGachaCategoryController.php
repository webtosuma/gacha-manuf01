<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminGachaCategoryRequest;
use App\Models\GachaCategory;
/*
| =============================================
|  サイト管理者 ガチャのカテゴリー コントローラー
| =============================================
*/
class AdminGachaCategoryController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gacha_categories = GachaCategory::all();

        // dd($gacha_categories);

        return view('admin.category.index', compact('gacha_categories') );
    }


    /**
     * 表示
     *
     * @param  \App\Models\GachaCategory $gacha_category
     * @return \Illuminate\Http\Response
     */
    public function show( GachaCategory $gacha_category )
    {
        return view('admin.category.show', compact('gacha_category'));
    }



    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gacha_category = new GachaCategory([
            'is_published' => 0,
        ]);

        // dd($gacha_category->noImage());
        return view('admin.category.create', compact('gacha_category'));
    }



    /**
     * 登録
     *
     * @param  \Illuminate\Http\AdminGachaCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminGachaCategoryRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $gacha_category = new GachaCategory( $inputs );
        $gacha_category->save();


        # 返信メッセージ
        return redirect()->route('admin.gacha_category')
        ->with(['alert-primary'=>'お知らせ情報を新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\GachaCategory $gacha_category
     * @return \Illuminate\Http\Response
     */
    public function edit(GachaCategory $gacha_category)
    {
        return view('admin.category.edit', compact('gacha_category'));
    }



    /**
     * 更新
     *
     * @param  \Illuminate\Http\AdminGachaCategoryRequest $request
     * @param  \App\Models\GachaCategory $gacha_category
     * @return \Illuminate\Http\Response
     */
    public function update(AdminGachaCategoryRequest $request, GachaCategory $gacha_category)
    {
        // dd($request->all());
        # 入力データの加工
        $inputs = self::processingInputs( $request, $gacha_category );

        # DBデータの更新
        $gacha_category->update( $inputs );


        # リダイレクト
        return redirect()->route('admin.gacha_category')
        ->with(['alert-warning'=>'お知らせ情報を更新しました。']);
    }



    /**
     * 削除
     *
     * @param  \App\Models\GachaCategory $gacha_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(GachaCategory $gacha_category)
    {
        $gacha_category->delete();


        # リダイレクト
        return redirect()->route('admin.gacha_category')
        ->with(['alert-danger'=>'お知らせ情報を削除しました。']);
    }



    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gacha $gacha //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $gacha_category=null )
    {
        $inputs = $request->only(
            'title',       //題名
            'body' ,       //本文
            'image',       //画像
            'is_slide',    //スライドの表示有無
            'published_at',//公開日時
        );

        # スライドの表示有無
            $inputs['is_slide'] = $request->is_slide==true ;

        # ストレージ更新の処理（商品説明）body
            $old_text = $gacha_category? $gacha_category->body: null;  //更新前のファイルパステキスト
            $new_text = $request->body;             //新しい入力テキスト
            $dir = 'upload/gacha_category/body/';      //保存先ディレクトリ
            $inputs['bosy'] = Method::uploadStorageText($dir, $new_text, $old_text);


        # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/gacha_category/image/';             //保存先ディレクトリ
            $request_file    = $request->file('image');     //画像のリクエスト
            $old_image_path  = $gacha_category? $gacha_category->image: null; //更新前の画像パス
            $image_dalete    = $request->image_dalete;      //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;       //コピー用画像パス

            $inputs['image'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);


        # 公開設定
            $published_at = $gacha_category? $gacha_category->published_at :NULL;
            $is_published = $gacha_category? $gacha_category->is_published :NULL;//公開中か否か

            // 公開[1](前回が「公開」でないとき)
            if( $request->is_published==1 && !$is_published ){
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

            $inputs['published_at'] = $published_at;
        //


        return $inputs;
    }
}
