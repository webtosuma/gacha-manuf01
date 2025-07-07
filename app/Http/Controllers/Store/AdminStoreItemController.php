<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminLogController;//操作ログ
use App\Http\Controllers\Method;//コントローラーメソッド

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\StoreItem;
use App\Models\Prize;
/*
| =============================================
|  ストアーAdmin ストアー商品 コントローラー
| =============================================
*/
class AdminStoreItemController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\View\View
    */
    public function index(Request $request)
    {
        $category_id = $request->category_id;

        # [ルーティング]ガチャ商品から登録(未登録の場合は、非表示)
        $r_prize_create = Prize::first() ? route('admin.store_item.prize.create') : null;

        return view('store_admin.store_item.index',compact(
            'category_id', 'r_prize_create',
        ));
    }



    /**
     * 新規作成
     *
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        # カテゴリーコードの認証
        $category_id = $request->category_id;

        # 新規作成モデル
        $store_item = new StoreItem([
            'category_id' => $category_id ?? GachaCategory::first()->id,
        ]);

        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::all();


        return view('store_admin.store_item.create',compact(
            'store_item','categories',
        ));
    }




    /**
     * 登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );
        $inputs['code'] = StoreItem::CreateCode();//商品コードの登録

        # DBデータの新規登録
        $store_item = new StoreItem( $inputs, $store_item=null );
        $store_item->save();

        # ストレージ画像ファイルの更新（イメージ画像）
        $store_item->uploadImages($request);

        # 操作ログの更新
        AdminLogController::createLog( 'store_item.create', $store_item->id );

        $request->session()->regenerateToken();// 二重送信防止


        # 返信メッセージ
        return redirect()->route('admin.store_item',['category_id'=>$store_item->category_id])
        ->with(['alert-success'=>'ストアー商品情報を登録しました']);
    }



    /**
     * 基本情報　編集
     *
     * @param  StoreItem  $store_item
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreItem $store_item)
    {
        # カテゴリーデータ(select要素用)
        $categories = GachaCategory::all();

        return view('store_admin.store_item.edit', compact(
            'store_item','categories',
        ));
    }



    /**
     * 基本情報　更新
     *
     * @param  Request $request
     * @param  StoreItem  $store_item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreItem $store_item)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $store_item );

        # DBデータの更新
        $store_item->update($inputs);

        # ストレージ画像ファイルの更新（イメージ画像）
        $store_item->uploadImages($request);


        # 操作ログの更新
        AdminLogController::createLog( 'store_item.edit', $store_item->id );

        $request->session()->regenerateToken();// 二重送信防止

        return redirect()->route('admin.store_item',['category_id'=>$store_item->category_id])
        ->with(['alert-warning'=>'ストアー商品情報を更新しました']);
    }



        /**
         * 入力データの加工 self::processingInputs( $request )
         *
         * @param \Illuminate\Http\Request $request
         * @param StoreItem $store_item //新規登録のとき===null
         * @return Array
         */
        public function processingInputs( $request, $store_item=null )
        {
            // dd($request->all());
            $inputs = $request->only(
                'category_id'      ,//カテゴリー　リレーション
                'prize_id'         ,//ガチャ用商品リレーション

                'item_name'        ,//アイテム名
                'brand_name'       ,//ブランド名
                'discription'      ,//説明文

                'price'            ,//販売価格
                'count'            ,//在庫数
                'points_redemption',//還元ポイント

                'is_slide'         ,//スライド表示
                'published_at'     ,//公開日時
                'back_in_stock_at' ,//再入荷日時
            );


            # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
            $decode_params = ['item_name','brand_name','discription'];
            foreach ($decode_params as $param) {
                $inputs[$param] = urldecode($inputs[$param]);
            }


            # 公開設定
            $inputs['published_at'] = self::processingPublished( $request, $store_item=null );


            # 再入荷
            // if( $store_item && $store_item->count<1 && $inputs['count']>0){
            //     $inputs['back_in_stock_at'] = now();
            // }


            return $inputs;
        }


        /* 公開設定 */
        public static function processingPublished( $request, $store_item=null )
        {
            $published_at = $store_item? $store_item->published_at :NULL;
            $is_published = $store_item? $store_item->is_published :NULL;//公開中か否か

            // 公開[1](前回が「公開」でないとき)
            if( $request->is_published==1 && !$is_published ){
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

            return $published_at;
        }




    /**
     * 動画　編集
     *
     * @param  StoreItem  $store_item
     * @return \Illuminate\Http\Response
     */
    public function movie_edit(StoreItem $store_item)
    {
        return view('store_admin.store_item.movie_edit', compact('store_item'));
    }



    /**
     * 動画情報　更新
     *
     * @param  Request $request
     * @param  StoreItem  $store_item
     * @return \Illuminate\Http\Response
     */
    public function movie_update(Request $request, StoreItem $store_item)
    {
        # PCモバイル動画の更新
        if( $request->youtube_url )
        {
            $movie = $request->youtube_url;
            $store_item->update(compact('movie'));
        }
        elseif( $request->movie || $request->movie_dalete )
        {
            # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/store_item/movie/';                          //保存先ディレクトリ
            $request_file    = $request->file('movie');                 //画像のリクエスト
            $old_image_path  = $store_item ? $store_item->movie : null; //更新前の画像パス
            $image_dalete    = $request->movie_dalete;                  //画像を削除するか否か

            $movie = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete) ?? '';

            # DBデータの更新
            $store_item->update(compact('movie'));
        }


        # 操作ログの更新
        AdminLogController::createLog( 'store_item.movie.edit', $store_item->id );

        $request->session()->regenerateToken();// 二重送信防止

        return redirect()->route('admin.store_item')
        ->with(['alert-warning'=>"『{$store_item->name}』の動画を更新しました"]);
    }
}
