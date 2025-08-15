<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Method;//コントローラーメソッド
/*
| =============================================
|  EC 販売アイテム　モデル
| =============================================
*/
class StoreItem extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    protected $fillable = [
        'code'             ,//商品コード
        'category_id'      ,//カテゴリー　リレーション
        'prize_id'         ,//ガチャ用商品リレーション

        'item_name'        ,//アイテム名
        'images'           ,//画像(複数)
        'movie'            ,//動画
        'brand_name'       ,//ブランド名
        'discription'      ,//説明文

        'price'            ,//販売価格
        'count'            ,//在庫数
        'points_redemption',//還元ポイント

        'is_slide'         ,//スライド表示
        'published_at'     ,//公開日時
        'back_in_stock_at' ,//再入荷日時
        'purchased_count'  ,//購入された回数
        'showed_count'     ,//表示した回数
    ];



    /** アクセサーをJSONに含める */
    protected $appends = [
        'name',              //アイテム名
        'image_paths',       //画像ファイルパス
        'ration',            //画像の比率
        'movie_path',        //動画ファイルパス
        'discription_text',  //ストレージ保存された文章（説明文）
        'is_sold_out',       //売り切れか否か
        'is_published',      //公開中かどうか
        'new_label_path',    //Newラベル(新規公開のみ)

        'r_show',            //[ルーティング]詳細
        'r_admin_edit',      //[ルーティング]Admin編集
        'r_admin_movie_edit',//[ルーティング]Admin動画編集
    ];




    /** 型指定 */
    protected $casts = [
        'published_at'     => 'datetime',//公開日時
        'back_in_stock_at' => 'datetime',//再入荷日時
    ];


    /**
     * 並び替え選択肢 orders
     * @return Array
    */
    public static function orders()
    {
        return [
            ['key'=>'desc_publised',         'label'=>'新着順'],
            ['key'=>'desc_purchased_count',  'label'=>'人気順'],
            ['key'=>'desc_price',            'label'=>'高い順'],
            ['key'=>'asc_price',             'label'=>'安い順'],
            ['key'=>'desc_points_redemption','label'=>'還元ptが多い順'],
            ['key'=>'asc_count',             'label'=>'在庫が少ない順'],
        ];
    }

    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =8;
        while ( !$code ) {
            $str = 'item_'.Str::random($n);
            $model = self::where('code', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * GachaCategoryモデル リレーション
         * @return \App\Models\GachaCategory
        */
        public function category(){
            return $this->belongsTo(GachaCategory::class, 'category_id')
            ->withTrashed(); // 削除を含む
        }


        /**
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class)
            ->withTrashed(); // 削除を含む
        }


        /**
         * StoreKeepモデル リレーション (カート商品＋販売済商品)
         * @return \App\Models\StoreKeep
        */
        public function store_keeps(){
            return $this->hasMany(StoreKeep::class);
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 公開中かどうか is_published
         * @return Boolean
        */
        public function getIsPublishedAttribute()
        {
            return $this->published_at && $this->published_at <= now()->format('Y-m-d H:i:s') ;
        }



        /**
         * アイテム名 name //ガチャ用商品とリレーションしている時は、ガチャ用商品の名前を利用する
         * @return String
        */
        public function getNameAttribute()
        {
            $name = $this->prize ? $this->prize->name : $this->item_name;
            return $name ? $name : '*Unregistered';
        }



        /** 画像なしの時の画像 */
        public static function noImage(){ return asset( 'storage/site/image/no_image.jpg' );}


        /**
         * 画像ファイルパス image_paths
         *
         * @return Array
        */
        public function getImagePathsAttribute()
        {
            $array = [];

            # ガチャ商品とのリレーションがあるとき
            if($this->prize){ $array[] = $this->prize->image_path; }

            # 保存済み画像配列を取得
            $imgs_array = Method::getStorageObjData( $this->images_json_path ) ?? $this->defaultStorageObjData();

            # アイテム画像パスの保存
            foreach ($imgs_array as $path)
            {
                if( Storage::exists( $path ) ){
                    $array[] = asset( 'storage/'.$path );
                }
            }

            return $array;
        }

        /**
         * 画像ファイルパス(単数) image_path
         *
         * @return Array
        */
        public function getImagePathAttribute()
        {
            return isset($this->image_paths[0]) ? $this->image_paths[0] : null;
        }


        /**
         * 画像の比率 ration
         * @return Striong
        */
        public function getRationAttribute() { return config('store.item_ratio') ; }


        /**
         * 動画ファイルパス movie_path
         * @return String
        */
        public function getMoviePathAttribute()
        {
            $path = $this->movie;
            return $this->movie && Storage::exists($path) ?
            asset( 'storage/'.$path ) :  null;
        }



        /**
         * Youtube動画URL youtube_url
         * @return String
        */
        public function getYoutubeUrlAttribute()
        {

            if (  strpos( $this->movie, "https://www.youtube.com/shorts" ) !== 0 ){ return null; }


            return $this->movie ;
        }



        /**
         * ストレージ保存された文章（説明文） discription_text
         * @return String
         */
        public function getDiscriptionTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->discription;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);
            return Storage::exists($path) ? Storage::get($path) : $text;
        }




        /**
         * 売り切れか否か is_sold_out
         * @return String //売り切れ理由
         */
        public function getIsSoldOutAttribute()
        {
            # 在庫切れ
            if( $this->count<1 ){
                return '在庫切れ';
            }

            # カテゴリーがないor非公開
            if( $this->category->deleted_at || !$this->category->is_published ){
                return '現在、こちらのカテゴリー商品を販売することができません。';
            }

            # ガチャ用商品が非公開
            if( ! $this->is_published ){
                return '現在、こちらの商品を販売することができません。';
            }


            return false;
        }


        /**
         * Newラベル(新規公開のみ) new_label_path
         * @return String
        */
        public function getNewLabelPathAttribute()
        {
            $image_path = 'storage/site/image/new_icon/store.png';

            $published_at = $this->published_at ? $this->published_at->toDateTimeString() : '';
            $new_start_at = now()->subday(7)->toDateTimeString();//減算
            $bool = $new_start_at < $published_at;
            return $bool ? asset( $image_path ) : '';
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー(ルーティング)
    |--------------------------------------------------------------------------
    |
    |
    */
        /** [ルーティング]詳細 r_show */
        public function getRShowAttribute()
        { return route('store.show',$this->code); }

        /** [ルーティング]Admin編集 r_admin_edit */
        public function getRAdminEditAttribute()
        { return route('admin.store_item.edit',$this->id); }


        /** [ルーティング]Admin動画編集 r_admin_movie_edit */
        public function getRAdminMovieEditAttribute()
        { return route('admin.store_item.movie.edit',$this->id); }

    /*
    |--------------------------------------------------------------------------
    | 画像 取得・保存メソッド
    |--------------------------------------------------------------------------
    |
    |
    */
        /** 複数画像保存JSONファイルパス images_json_path*/
        public function getImagesJsonPathAttribute()
        { return "upload/store_item/{$this->id}/img.json";}


        /** 複数画像の保存数JSON images_count*/
        public function getImagesCountAttribute(){ return 10;}


        /** デフォルトの画像配列 */
        public function defaultStorageObjData()
        {
            $result_array = [];
            for ($num=0; $num < $this->images_count; $num++) { $result_array[] = null; }
            return $result_array;
        }


        /**
         * 管理者ページで使う画像パス admin_image_paths
         */
        public function getAdminImagePathsAttribute()
        {
            # 画像の表示数の調整
            $result_array = [];
            $count = $this->images_count;
            for ($num=0; $num < $count; $num++) {

                $result_array[]  = isset($this->image_paths[$num]) ? $this->image_paths[$num] : '';

            }

            return $result_array;
        }



        /** 複数画像の保存 */
        public function uploadImages($request)
        {
            # 画像の保存
            $array = [];
            $count = $this->images_count;

            # リクエスト画像のストレージ保存
            for ($num=0; $num < $count; $num++) {

                $array[]  = self::uploadImagefileMethod($request, $num);

            }

            # 空データの削除
            $array = array_filter($array);
            $array = array_values($array); // キーを振り直す（オプション）
            $result_array = $array;

            # 画像配列データをストレージ保存
            $put = Method::putStorageObjData( $this->images_json_path, $result_array );

            return true;
        }

            /** 単数画像ファイルの更新メソッド */
            public function uploadImagefileMethod($request, $num)
            {
                # 保存済み画像配列を取得
                $imgs_array = Method::getStorageObjData( $this->images_json_path ) ?? $this->defaultStorageObjData();


                # ストレージ画像ファイルの更新（イメージ画像）
                $param = 'images'.$num;
                $dir = 'upload/store_item/image/'.$param;      //保存先ディレクトリ
                $request_file    = $request->file($param);     //画像のリクエスト
                $old_image_path  = isset( $imgs_array[$num] ) ? $imgs_array[$num] : null;
                $image_dalete    = $request[$param.'_dalete']; //画像を削除するか否か
                $copy_image_puth = null;                       //コピー用画像パス

                return Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);
            }




    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ユーザー表示用スコープ ->forUserPublished()
        */
        public function scopeForUserPublished($query)
        {
            # カテゴリーリレーション(API用)
            $query->with('category');

            # 公開中
            $query->where('published_at','<>',null)->where('published_at','<=',now());//公開中

            # カテゴリーが公開中
            $query->whereHas('category', function ($query){
                $query->where('is_published', 1);
            });
        }



        /**
         * 検索スコープ(user admin兼用) ->searchQuery($request)
        */
        public function scopeSearchQuery($query,$request)
        {

            #　キーワード検索
            if( $request->keyword )
            {
                // $query->where( 'item_name','like','%'.$request->keyword.'%' );

                $query->where(function($q) use ($request) {

                    $q->where('item_name' , 'like', '%' . $request->keyword . '%')
                    ->orWhere('brand_name', 'like', '%' . $request->keyword . '%')
                    ;

                });

            }

            # カテゴリーの選択
            if(  $request->category_id ){
                $query->where('category_id', $request->category_id);
            }

            # 公開状態
            switch ( $request->published_status ) {
                case 'published'://公開中
                    $query->where('published_at','<>',null)->where('published_at','<=',now());
                    break;

                case 'reserv_publish'://公開予約
                    $query->where('published_at','>',now());
                    break;

                case 'an_publish'://未公開
                    $query->where('published_at',null);
                    break;

                case 'sold_out'://売り切れ
                    $query->where('count',0);
                    break;

                default:
                    # code...
                    break;
            }


            # 還元ポイント順
            if( $request->order_points_redemption ){
                $query->orderBy('points_redemption', $request->order_points_redemption);
            }


            # 販売価格順
            if( $request->order_price ){
                $query->orderBy('price', $request->order_price);
            }

            # 在庫順
            if( $request->order_count ){
                $query->orderBy('count', $request->order_count);
            }


            # 並び替え(order)
            switch ($request->order)
            {
                case 'desc_purchased_count': /*人気順*/
                    $query->orderByDesc('purchased_count');
                    break;

                case 'desc_price': /*高い順*/
                    $query->orderByDesc('price');
                    break;

                case 'asc_price': /*安い順*/
                    $query->orderBy('price');
                    break;

                case 'desc_points_redemption': /*還元ポイントが多い順*/
                    $query->orderByDesc('points_redemption');
                    break;

                case 'asc_count': /*在庫が少ない順*/
                    $query->orderBy('count');
                    break;
            }


            # 登録が新しい順
            $query->orderByDesc('published_at')->orderByDesc('created_at');

            # リレーション
            $query->with('prize','category');

        }




    /*
    |--------------------------------------------------------------------------
    | エラーメッセージ
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * エラーメッセージ $store_item->ErrCheckMessage($request)
         */
        public function ErrCheckMessage($request)
        {
            # ログインユーザー
            $user = Auth::user();

            # メッセージ
            $message =  null;

            # ログアウト中のとき
            if(!$user){
                $message = 'この処理には、ログインが必要です。';
            }

            # 売り切れのとき
            if($this->is_sold_out){
                $message = $this->is_sold_out;
            }

            # 在庫数より購入数の方が大きいとき
            if($this->count < $request->count){
                $message = '在庫数より多い数量を購入することはできません。';
            }

            # 販売停止(非公開)のとき
            if( $this->published_at>now() || $this->published_at==null )
            {
                $message = '売切れ商品を購入することはできません。';
            }

            return $message;
        }


    /**/
}
