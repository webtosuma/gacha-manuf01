<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  Manufacturer用　ガチャタイトル筐体 モデル
| =============================================
*/
class ManufGachaTitleMachine extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'manuf_gacha_title_id',
        'gacha_id',
    ];


    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [
        'price',

        'category',       //カテゴリー
        'name',
        'key',
        'type',
        'one_play_point',
        'published_at',
        'sold_out_at',
        'is_sold_out',
        'is_published',
        'type_label',
        'type_label_admin',
        'min_tmine',
        'max_time',

        /*残数*/
        'remaining_ratio',
        'remaining_count',
        'max_count',
        'pending_count',     //待機中の数
        'max_purchase_count',//購入可能な数 
        'not_purchase',      //販売不可能か否か 

    ];


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */


        public function gacha_title()
        {
            return $this->belongsTo(
                ManufGachaTitle::class, 'manuf_gacha_title_id'
            )->withTrashed();
        }

        /**　ガチャ*/
        public function gacha()
        {
            return $this->belongsTo(Gacha::class)
            ->withTrashed();
        }

        /**　購入アイテム　*/
        public function purchase_items()
        {
            return $this->hasMany( 
                ManufPurchaseItem::class, 'machine_id'
            );
        }

        # ガチャ商品 g_prizes
        public function getGPrizesAttribute()
        {
            if (!$this->gacha) {
                return collect();
            }
    
            # prizeの登録が古い順
            return $this->gacha->g_prizes()
            ->with('prize')
            ->join('prizes', 'gacha_prizes.prize_id', '=', 'prizes.id')
            ->orderBy('prizes.created_at', 'asc')
            ->select('gacha_prizes.*')
            ->get();
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        # 一回のPLAY価格
        public function getPriceAttribute()
        {
            return $this->gacha_title->price;
        }


        # ガチャの種類 types
        public function getTypesAttribute()
        {
            return Gacha::types();
        }

        # ガチャの表示可能時間　一覧 times
        public function getTimesAttribute()
        {
            return Gacha::times();
        }

        # カテゴリー
        public function getCategoryAttribute()
        {
            return $this->gacha?->category;
        }

        # 名前
        public function getNameAttribute()
        {
            $count = ManufGachaTitleMachine::orderByDesc('id')->first()->id+1;
            return $this->gacha?->name ?? 'ガチャマシン'.$count;
        }

        # 認証キー
        public function getKeyAttribute()
        {
            return $this->gacha?->key;
        }

        # ガチャ種類
        public function getTypeAttribute()
        {
            return $this->gacha?->type;
        }

        # 1回プレイポイント
        public function getOnePlayPointAttribute()
        {
            return $this->gacha?->one_play_point;
        }

        # 公開日時
        public function getPublishedAtAttribute()
        {
            return $this->gacha?->published_at;
        }

        # 売り切れ日時
        public function getSoldOutAtAttribute()
        {
            return $this->gacha?->sold_out_at;
        }

        # 売り切れフラグ
        public function getIsSoldOutAttribute()
        {
            return $this->gacha?->is_sold_out;
        }

        # 公開中フラグ
        public function getIsPublishedAttribute()
        {
            return $this->gacha?->published_at !== null;
        }

        # ガチャ種類ラベル（フロント用）
        public function getTypeLabelAttribute()
        {
            return $this->gacha?->type_label ?? null;
        }

        # ガチャ種類ラベル（管理用）
        public function getTypeLabelAdminAttribute()
        {
            return $this->gacha?->type_label_admin ?? null;
        }


        # 表示時間下限 min_time
        public function getMinTimeAttribute()
        {
            return $this->gacha?->min_time
            ?? config( 'gacha.defaults.min_time', '00:00');
        }

        # 表示時間上限 max_time
        public function getMaxTimeAttribute()
        {
            return $this->gacha?->max_time
            ?? config( 'gacha.defaults.max_time', '24:00');
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー 残数
    |--------------------------------------------------------------------------
    |
    |
    */
        # 残数比率
        public function getRemainingRatioAttribute()
        {
            return $this->gacha?->remaining_ratio ?? null;
        }

        # 残数 remaining_count
        public function getRemainingCountAttribute()
        {
            return $this->gacha?->remaining_count ?? null;
        }

        # 総口数 max_count
        public function getMaxCountAttribute()
        {
            return $this->gacha?->max_count ?? null;
        }

        # 待機中の数 pending_count
        public function getPendingCountAttribute()
        {        
            # 待機中のタイムアウト時間   
            $timeout = now()->subSeconds( config('manuf.purchase.pending_timeout') );
            
            return $this->purchase_items()
            ->whereHas('history', function ( $query )use($timeout) {
                $query->where('status', 'pending')
                ->where( 'created_at', '>=',$timeout );//タイムアウト
            })->sum('count'); //PLAU数の合計数
        }
        

        # 購入可能な数 max_purchase_count
        public function getMaxPurchaseCountAttribute()
        {
            return ($this->remaining_count) - ($this->pending_count);
        }

        # 販売不可能か否か not_purchase
        public function getNotPurchaseAttribute(): bool
        { return ( 
            # ガチャ売り切れ
            $this->gacha->is_sold_out       
            # (在庫-待機中) < 1
            || $this->max_purchase_count<1  

            # [限定ガチャ]1回or10回限定
            || ( $this->gacha->type=='one_chance'  && $this->gacha->played_one_time )

            # [限定ガチャ]１回限定ガチャ
            || ( $this->gacha->type=='one_time'    && $this->gacha->played_one_time )

            # [限定ガチャ]一日一回限定限定ガチャ
            || ( $this->gacha->type=='only_oneday' && $this->gacha->played_only_oneday )

            # [限定ガチャ]新規登録ユーザー定限定ガチャ
            || ( $this->gacha->type=='only_new_user' 
                && ( Auth::user()->sevendays_affter_registar || $this->gacha->played_one_time )  
            )
            # [時間限定ガチャ]
            || (!$this->gacha->is_show_timezone) /*-- (時間帯限定)表示可能か否か --*/

        ); }

    /*
    |--------------------------------------------------------------------------
    | アクセサー Admin ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティング]show r_admin_show
         */
        public function getRAdminShowAttribute()
        {
            return route('admin.gacha_title.machine.show', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'machine' => $this->id,
            ]);
        }

        /**
         * [ルーティング]編集 r_admin_edit
         */
        public function getRAdminEditAttribute()
        {
            return route('admin.gacha_title.machine.edit', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'machine' => $this->id,
            ]);
        }

        /**
         * [ルーティング]更新 r_admin_update
         */
        public function getRAdminUpdateAttribute()
        {
            return route('admin.gacha_title.machine.update', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'machine' => $this->id,
            ]);
        }

        /**
         * [ルーティング]削除 r_admin_destroy
         */
        public function getRAdminDestroyAttribute()
        {
            return route('admin.gacha_title.machine.destroy', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'machine' => $this->id,
            ]);
        }

        /**
         * [ルーティング]コピー r_admin_copy
         */
        public function getRAdminCopyAttribute()
        {
            return route('admin.gacha_title.machine.copy', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'machine' => $this->id,
            ]);
        }

        
}
