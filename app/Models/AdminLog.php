<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  Admin 操作履歴　モデル
| =============================================
*/
class AdminLog extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'admin_id' ,// リレーションID
        'type_id'  ,// 履歴の種類
        'target_id',// 履歴に関係するデータの紐付け
    ];


    /* 履歴の種類 */
    public static function types() { return[
        /* ガチャ */
        [ 'id' => 'gacha.create',      'label' => 'ガチャ/新規作成', ],
        [ 'id' => 'gacha.edit',        'label' => 'ガチャ/基本情報編集', ],
        [ 'id' => 'gacha.copy',        'label' => 'ガチャ/コピー作成', ],
        [ 'id' => 'gacha.delete',      'label' => 'ガチャ/削除', ],
        [ 'id' => 'gacha.prize',       'label' => 'ガチャ/商品登録', ],
        [ 'id' => 'gacha.movie',       'label' => 'ガチャ/演出動画編集', ],
        [ 'id' => 'gacha.discription', 'label' => 'ガチャ/詳細説明編集', ],
        [ 'id' => 'gacha.published',   'label' => 'ガチャ/公開設定', ],
        /* カテゴリー */
        [ 'id' => 'category.create',   'label' => 'カテゴリー/新規作成', ],
        [ 'id' => 'category.edit',     'label' => 'カテゴリー/編集', ],
        [ 'id' => 'category.delete',   'label' => 'カテゴリー/削除', ],
        /* 商品 */
        [ 'id' => 'prize.create',      'label' => '商品/新規作成', ],
        [ 'id' => 'prize.edit',        'label' => '商品/編集', ],
        [ 'id' => 'prize.delete',      'label' => '商品/削除', ],
        [ 'id' => 'prize.copy',        'label' => '商品/コピー作成', ],
        // [ 'id' => 'prize.inport',      'label' => '商品/一括登録', ],
        [ 'id' => 'prize.download',    'label' => '商品/ダウンロード', ],

        /* 演出動画 */
        [ 'id' => 'movie.create',      'label' => '演出動画/新規作成', ],
        [ 'id' => 'movie.edit',        'label' => '演出動画/編集', ],
        [ 'id' => 'movie.delete',      'label' => '演出動画/削除', ],
        /* お知らせ */
        [ 'id' => 'infomation.create', 'label' => 'お知らせ/新規作成', ],
        [ 'id' => 'infomation.edit',   'label' => 'お知らせ/編集', ],
        [ 'id' => 'infomation.delete', 'label' => 'お知らせ/削除', ],
        [ 'id' => 'infomation.email',  'label' => 'お知らせ/メール送信', ],
        /* 登録ユーザー */
        [ 'id' => 'user.add_point',   'label' => '登録ユーザー/ポイント付与', ],
        // [ 'id' => 'user.add_ticket',  'label' => '登録ユーザー/チケット付与', ],
        [ 'id' => 'user.delete',      'label' => '登録ユーザー/退会処理', ],
        [ 'id' => 'user.revival',     'label' => '登録ユーザー/退会解除', ],

        /* 文書設定 */
        [ 'id' => 'text.trems.create',          'label' => '利用規約/新規登録', ],
        [ 'id' => 'text.trems.update',          'label' => '利用規約/更新', ],
        [ 'id' => 'text.trems.delete',          'label' => '利用規約/削除', ],
        [ 'id' => 'text.privacy_policy.create', 'label' => 'プライバシポリシー/新規登録', ],
        [ 'id' => 'text.privacy_policy.update', 'label' => 'プライバシポリシー/更新', ],
        [ 'id' => 'text.privacy_policy.delete', 'label' => 'プライバシポリシー/削除', ],
        [ 'id' => 'text.tradelaw.create',       'label' => '特定商取引法に基づく表記/新規登録', ],
        [ 'id' => 'text.tradelaw.update',       'label' => '特定商取引法に基づく表記/更新', ],
        [ 'id' => 'text.tradelaw.delete',       'label' => '特定商取引法に基づく表記/削除', ],

        [ 'id' => 'text.guide.update',          'label' => '利用ガイド/更新', ],
        [ 'id' => 'text.sbg_license.update',    'label' => '古物商営業許可/更新', ],
        [ 'id' => 'text.meta.update',           'label' => 'メタ情報/更新', ],
        [ 'id' => 'text.note.update',           'label' => '商品購入に関する注意文/更新', ],
        [ 'id' => 'text.email_signature.update','label' => 'メール署名/更新', ],

        /* その他 */
        [ 'id' => 'back_ground.edit',  'label' => 'サイト背景/編集', ],
        [ 'id' => 'maintenance.edit',  'label' => 'メンテナンス設定/編集', ],

    ]; }


    /** 種類IDの配列 */
    public static function typeIdArray()
    {
        $array = [];
        foreach (self::types() as $type) { $array[] = $type['id']; }
        return $array;
    }


    /** アクセサーをJSONに含める */
    protected $appends = [
        'gacha',
        'category',
        'prize',
        'movie',
        'infomation',
        'user',
        'created_at_format', //作成日時フォーマット
        'type_label',        //種類ラベル
        'type_route',        //種類ルーティング
    ];




    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * Adminモデル リレーション
         * @return \App\Models\Admin
        */
        public function admin(){
            return $this->belongsTo(Admin::class);
        }


        /**
         * Gachaモデル リレーション
         * @return \App\Models\Gacha
        */
        public function with_gacha(){
            return $this->belongsTo(Gacha::class,'target_id')->withTrashed() ;
        }


        /**
         * GachaCategoryモデル リレーション
         * @return \App\Models\GachaCategory
        */
        public function with_category(){
            return $this->belongsTo(GachaCategory::class,'target_id')->withTrashed() ;
        }


        /**
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function with_prize(){
            return $this->belongsTo(Prize::class,'target_id')->withTrashed() ;
        }


        /**
         * Movieモデル リレーション
         * @return \App\Models\Movie
        */
        public function with_movie(){
            return $this->belongsTo(Movie::class,'target_id');
        }


        /**
         * Infomationモデル リレーション
         * @return \App\Models\Infomation
        */
        public function with_infomation(){
            return $this->belongsTo(Infomation::class,'target_id');
        }


        /**
         * Userモデル リレーション
         * @return \App\Models\User
        */
        public function with_User(){
            return $this->belongsTo(User::class,'target_id')->withTrashed() ;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー target
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ガチャ(種類が一致するとき) gacha
         * @return String
        */
        public function getGachaAttribute()
        {
            $key = 'gacha';
            return strpos($this->type_id, $key)===0 ? $this->with_gacha : null ;
        }


        /**
         * カテゴリー(種類が一致するとき) category
         * @return String
        */
        public function getCategoryAttribute()
        {
            $key = 'category';
            return strpos($this->type_id, $key)===0 ? $this->with_category : null ;
        }


        /**
         * 商品(種類が一致するとき) prize
         * @return String
        */
        public function getPrizeAttribute()
        {
            $key = 'prize';
            return strpos($this->type_id, $key)===0 ? $this->with_prize : null ;
        }


        /**
         * 演出動画(種類が一致するとき) movie
         * @return String
        */
        public function getMovieAttribute()
        {
            $key = 'movie';
            return strpos($this->type_id, $key)===0 ? $this->with_movie : null ;
        }


        /**
         * お知らせ(種類が一致するとき) infomation
         * @return String
        */
        public function getInfomationAttribute()
        {
            $key = 'infomation';
            return strpos($this->type_id, $key)===0 ? $this->with_infomation : null ;
        }


        /**
         * 登録ユーザー(種類が一致するとき) user
         * @return String
        */
        public function getUserAttribute()
        {
            $key = 'user';
            return strpos($this->type_id, $key)===0 ? $this->with_user : null ;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 作成日時フォーマット created_at_format
         * @return String
        */
        public function getCreatedAtFormatAttribute()
        {
            return $this->created_at->format('Y/m/d H:i:s');
        }

        /**
         * 種類ラベル type_label
         * @return String
        */
        public function getTypeLabelAttribute()
        {
            foreach ($this->types() as $type)
            {
                if($type['id']==$this->type_id){ return $type['label']; }
            }
            return null;
        }

        /**
         * 種類ルーティング type_route
         * @return String
        */
        public function getTypeRouteAttribute()
        {
            /* ガチャ */
            $key = 'gacha';
            if( strpos($this->type_id, $key)===0 ){
                return route('admin.gacha.show',$this->gacha) ;
            }

            /* カテゴリー */
            $key = 'category';
            if( strpos($this->type_id, $key)===0 ){
                return route('admin.category.edit',$this->category) ;
            }

            /* 商品 */
            $key = 'prize';
            if( strpos($this->type_id, $key)===0 ){
                return route('admin.prize.edit',$this->prize) ;
            }

            /* 動画 */
            $key = 'movie';
            if( $this->movie && strpos($this->type_id, $key)===0 ){
                return route('admin.movie.edit',$this->movie) ;
            }

            /* お知らせ */
            $key = 'infomation';
            if( $this->infomation && strpos($this->type_id, $key)===0 ){
                return route('admin.infomation.show',$this->infomation) ;
            }

            /* 登録ユーザー */
            $key = 'user';
            if( $this->user && strpos($this->type_id, $key)===0 ){
                return route('admin.user.show',$this->user) ;
            }


        }

}
