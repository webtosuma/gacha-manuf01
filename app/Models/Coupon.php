<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  クーポン　モデル
| =============================================
*/
class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'code'           ,//コード
        'title'          ,//タイトル
        'prize_id'       ,//ガチャ商品ID
        'point'          ,//付与ポイント
        'count'          ,//利用可能な回数
        'user_type'      ,//利用者の種類
        'target_user_ids',//対象ユーザーのID
        'is_use_code'    ,//コードを利用するか
        'is_done'        ,//回数制限に達したか否か
        'published_at'   ,//公開日時
        'expiration_at'  ,//有効期限
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\CouponFactory::new();
    }


    /** Carbonオブジェクトとして利用 */
    protected $casts = [
        'published_at'  => 'datetime',//公開設定(利用しない->非公開*消さない)
        'expiration_at' => 'datetime',//有効期限
    ];


    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =12;
        while ( !$code ) {
            $str = Str::random($n);
            $model = self::where('code', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }




    /** アクセサーをJSONに含める */
    protected $appends = [
        'user_types',           //利用者の種類
        'image_path',           //画像ファイルパス
        'discription_text',     //提供物の説明文
        'service',              //サービス(商品orポイント)
        'expiration_at_format', //有効期限フォーマット
        'published_at_format',  //公開日時フォーマット
        'published_state',      //公開状態(0:非公開 1:公開 2:公開予約)
        'remaining_count',      //残り回数
        'admin_remaining_count',//残り回数(Admin 先着用)
        'histories_count',       //履歴数(利用数)

        'is_published',         //公開中かどうか
        'is_expiration_done',   //有効期限が切れているか否か
        'is_new',               //newか否か

        'r_edit',               //[ルーティング] 編集
        'r_destroy',            //[ルーティング] 削除
        'r_copy',               //[ルーティング] コピー
    ];




    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */


        /**
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class)
            ->withTrashed();//削除済みも含む
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 利用者の種類 user_types
         */
        public function getUserTypesAttribute()
        {
            return [
                'user'     => 'おひとり様回数',
                'all_user' => '先着回数',
            ];
        }




        /*像なしの時の画像 */
        public static function pointImage(){ return asset( 'storage/site/image/point_icon/index.png' );}

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->prize ? $this->prize->image_path : self::pointImage();
        }



        /**
         * 提供物の説明文 discription_text
         * @return String
         */
        public function getDiscriptionTextAttribute()
        {
            return $this->prize
            ? "『{$this->prize->name}』をプレゼントいたします。"
            : "『{$this->point}pt』をプレゼントいたします。";
        }



        /**
         * サービス(商品orポイント) service
         * @return String
         */
        public function getServiceAttribute()
        {
            return $this->prize ? 'prize' : 'point' ;
        }


        /**
         * 商品コード prize_code
         * @return String
         */
        public function getPrizeCodeAttribute()
        {
            return $this->prize ? $this->prize->code : '' ;
        }


        /**
         * 残り回数 remaining_count
         * @return Integer //(終了の時は、felseを返す)
        */
        public function getRemainingCountAttribute()
        {
            # 回数指定なし(true)
            if( $this->count==0 ){ return true; }


            # 回数指定：全利用回数(all_user)のとき
            if( $this->user_type == 'all_user' )
            {
                # 利用済みか？
                $user = Auth::user();
                $u_history = CouponHistory::where('coupon_id',$this->id)
                ->where('user_id',$user->id)->first();
                if($u_history){ return false; }

                $history_count = CouponHistory::where('coupon_id',$this->id)
                ->count();

                $count = $this->count - $history_count;
                return $count>0 ? $count : false;
            }


            # 回数指定：ユーザー別利用回数(user)のとき
            if( $this->user_type == 'user' )
            {
                $user = Auth::user();
                $history_count = CouponHistory::where('coupon_id',$this->id)
                ->where('user_id',$user->id)
                ->count();

                $count = $this->count - $history_count;
                return $count>0 ? $count : false;
            }

            return false;
        }

        /**
         * 残り回数(Admin 先着用) admin_remaining_count
         * @return Integer //(終了の時は、felseを返す)
        */
        public function getAdminRemainingCountAttribute()
        {
            # 回数指定なし(true)
            if( $this->count==0 ){ return true; }


            # 回数指定：全利用回数(all_user)のとき
            if( $this->user_type == 'all_user' )
            {
                $history_count = CouponHistory::where('coupon_id',$this->id)
                ->count();

                $count = $this->count - $history_count;
                return $count>0 ? $count : false;
            }


            # 回数指定：ユーザー別利用回数(user)のとき
            if( $this->user_type == 'user' )
            {
               return $this->count;
            }

            return false;
        }



        /**
         * 有効期限フォーマット expiration_at_format
         * @return String
        */
        public function getExpirationAtFormatAttribute()
        {
            return $this->expiration_at ? $this->expiration_at->format('Y年m月d日 H:i') : null ;
        }



        /**
         * 公開フォーマット published_at_format
         * @return String
        */
        public function getPublishedAtFormatAttribute()
        {
            return $this->published_at ? $this->published_at->format('Y年m月d日 H:i') : null ;
        }


        /**
         * 履歴数(利用数) histories_count
         * @return String
        */
        public function getHistoriesCountAttribute()
        {
            return CouponHistory::where('coupon_id',$this->id)->count();
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー 否か
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 公開中か否か is_published
         * @return String
        */
        public function getIsPublishedAttribute()
        {
            return $this->published_at && $this->published_at <= now()->format('Y-m-d H:i:s') ;
        }


        /**
         * 公開状態 published_state
         * @return Integer (0:非公開 1:公開 2:公開予約)
        */
        public function getPublishedStateAttribute()
        {
            # 非公開
            if( !$this->published_at )
            { return 0; }

            # 公開
            elseif( $this->published_at <= now()->format('Y-m-d H:i:s') )
            { return 1; }

            # 公開予約
            else{ return 2; }
        }


        /**
         * 利用回数制限があるか否か is_count
         * @return String
        */
        public function getIsCountAttribute()
        {
            return $this->count>0;
        }




        /**
         * 有効期限があるか否か is_expiration
         * @return String
        */
        public function getIsExpirationAttribute()
        {
            return $this->expiration_at ? true : false ;
        }

        /**
         * 有効期限が切れているか否か is_expiration_done
         * @return String
        */
        public function getIsExpirationDoneAttribute()
        {
            if(!$this->expiration_at){ return false; }

            return $this->expiration_at < now()->format('Y-m-d H:i:s') ;
        }


        /**
         * 公開中か否か is_new
         * @return String
        */
        public function getIsNewAttribute()
        {
            // return isset($this->published_at);
            return $this->published_at
            && $this->published_at <= now()->format('Y-m-d H:i:s')
            && $this->published_at > now()->subDay(7)->format('Y-m-d H:i:s') ;
        }
    /*
    |--------------------------------------------------------------------------
    | アクセサー ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティング]編集 r_edit
         * @return String
        */
        public function getREditAttribute() { return route('admin.coupon.edit',$this->id); }

        /**
         * [ルーティング]削除 r_destroy
         * @return String
        */
        public function getRDestroyAttribute() { return route('admin.coupon.destroy',$this->id); }


        /**
         * [ルーティング]コピー r_copy
         * @return String
        */
        public function getRCopyAttribute() { return route('admin.coupon.copy',$this->id); }


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
            $now_format = now()->format('Y-m-d H:i:s');

            // # 利用回数期限切れでない
            $query->where('is_done',0);

            # 公開済みのみ
            $query->where('published_at','<=',now());

            # 有効期限内のみ
            $query->where('expiration_at','>=',now());

            # 新しい順
            $query->orderByDesc('published_at');
            $query->orderByDesc('created_at');
        }



        /**
         * キーワード(key_words)から検索するメソッド
         *
         * @param App\Models\Recruit::query $query
         * @param \Illuminate\Http\Request $req
         * @return App\Models\Recruit::query
         */
        public static function scopeKeyWordSearch( $query, $req )
        {
            #検索パラメータが存在するか
            if( !$req->has('keyword') ){ return; }

            #文字列を配列へ変換
            $key_words = self::ArrayConvertString( $req->keyword );

            #検索条件の絞り込み(全ての条件に該当するデータを検索:and)
            foreach ($key_words as $key_word) {

                $query->where(function($q) use ($key_word) {

                    $q->where('code', 'like', '%' . $key_word . '%')
                    ->orWhere('title',  'like', '%' . $key_word . '%')
                    ;

                });

            }
        }

        /**
        * 文字列を配列へ変換
        *
        * @param  String $string
        * @return Array
        */
       public static function ArrayConvertString($string)
       {
           $string = str_replace('　',' ',$string);
           $array  = explode(' ',$string);
           return $array;
       }

    /* ~ */
}
