<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'remaining_ratio',
        'remaining_count',
        'max_count',
        'min_tmine',
        'max_time',
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
            return $this->belongsTo(ManufGachaTitle::class, 'manuf_gacha_title_id')
            ->withTrashed();
        }

        public function gacha()
        {
            return $this->belongsTo(Gacha::class)
            ->withTrashed();
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
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

        # 残数比率
        public function getRAdminemainingRatioAttribute()
        {
            return $this->gacha?->remaining_ratio ?? null;
        }

        # 残数
        public function getRAdminemainingCountAttribute()
        {
            return $this->gacha?->remaining_count ?? null;
        }

        # 総口数
        public function getMaxCountAttribute()
        {
            return $this->gacha?->max_count ?? null;
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
    | アクセサー ルーティング
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
