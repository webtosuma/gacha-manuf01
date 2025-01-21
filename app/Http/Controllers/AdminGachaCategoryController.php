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
        $gacha_categories = GachaCategory::orderBy('created_at')
        ->get();

        // dd($gacha_categories);

        return view('admin.category.index', compact('gacha_categories') );
    }



    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # 新規作成ガチャデータ
        $gacha_category = new GachaCategory([
            'is_published' => 0,
        ]);


        # カテゴリー内での公開中ガチャ数[カテゴリー制限]
        $published_count = GachaCategory::where('is_published',true)
        ->get()->count();

        #公開制限[カテゴリー制限]
        $limit = 2;
        $restriction = env('LIMIT_GACHA_COUNT') ? $published_count>=$limit : false;
        $restriction = $gacha_category->is_published ? false : $restriction;//公開中のカテゴリは、公開ボタン制限なし


        return view('admin.category.create', compact('gacha_category','restriction'));
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
        return redirect()->route('admin.category')
        ->with(['alert-primary'=>'ガチャのカテゴリーを新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\GachaCategory $gacha_category
     * @return \Illuminate\Http\Response
     */
    public function edit(GachaCategory $gacha_category)
    {
        # カテゴリー内での公開中ガチャ数[カテゴリー制限]
        $published_count = GachaCategory::where('is_published',true)
        ->get()->count();
        // $published_count = 1;

        #公開制限[カテゴリー制限]
        $limit = env('LIMIT_CATEGORY_COUNT');
        $restriction = $limit ? $published_count>=$limit : false;
        $restriction = $gacha_category->is_published ? false : $restriction;//公開中のカテゴリは、公開ボタン制限なし


        return view('admin.category.edit', compact('gacha_category','restriction'));
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
        return redirect()->route('admin.category')
        ->with(['alert-warning'=>'ガチャのカテゴリーを更新しました。']);
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
        return redirect()->route('admin.category')
        ->with(['alert-danger'=>'ガチャのカテゴリーを削除しました。']);
    }



    /**
     * 編集(すべて)
     * @return \Illuminate\Http\Response
     */
    public function all_edit()
    {
        $gacha_category = new GachaCategory();

        return view('admin.category.all_edit',compact('gacha_category'));
    }




    /**
     * 更新(すべて)
     *
     * @param  Request $request
     * @param  \App\Models\GachaCategory $gacha_category
     * @return \Illuminate\Http\Response
     */
    public function all_update(Request $request)
    {

        if( $request_file = $request->file('bg_image') )
        {
            $dir = 'upload/gacha_category/bg_image';
            $file_name = 'all.jpg';

            // 画像のアップロード
            $image_path =  $request_file->storeAs($dir,$file_name );
        }


        # リダイレクト
        return redirect()->route('admin.category')
        ->with(['alert-warning'=>'ガチャのカテゴリーを更新しました。']);
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
            'name',        //名前
            'code_name',   //'コードネーム（ルーティング用）'
            'bg_image' ,   //'背景画像'
            'is_published',//公開(bool)
        );


        # ストレージ画像ファイルの更新（イメージ画像）
        $param = 'bg_image';
        $dir = 'upload/gacha_category/'.$param;        //保存先ディレクトリ
        $request_file    = $request->file($param);     //画像のリクエスト
        $old_image_path  = $gacha_category? $gacha_category->bg_image: null; //更新前の画像パス
        $image_dalete    = $request[$param.'_dalete']; //画像を削除するか否か
        $copy_image_puth = $request->copy_image_puth;  //コピー用画像パス
        $inputs[$param] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth) ?? '';


        return $inputs;
    }
}
