<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  Manufacturer用　ガチャタイトル商品 モデル
| =============================================
*/
class ManufGachaTitlePrize extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'manuf_gacha_title_id',
        'prize_id',
        'published_at',
        'order',
    ];



    protected $casts = [
        'published_at' => 'datetime',
    ];



    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [
        'category'  ,//カテゴリー
        'code',      //商品コード
        'name',      //名前
        'image_path',
        'rank_id',   //ランクID
        'discription_text',//ストレージ保存された文章（説明文）
        'discription_icon_path',//説明文モーダルアイコン

        'r_edit',
        'r_update',
        'r_destroy',
        'r_copy',
    ];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        public function gachaTitle()
        {
            return $this->belongsTo(ManufGachaTitle::class, 'manuf_gacha_title_id')
            ->withTrashed();
        }

        public function prize()
        {
            return $this->belongsTo(Prize::class)
            ->withTrashed();
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー(Prize情報)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * カテゴリー category
         * @return int|null
         */
        public function getCategoryAttribute(){
            return $this->prize?->category ?? null;
        }


        /**
         * 商品コード code
         * @return string|null
         */
        public function getCodeAttribute(){
            return $this->prize?->code ?? null;
        }


        /**
         * 名前 name
         * @return string|null
         */
        public function getNameAttribute(){
            return $this->prize?->name ?? null;
        }


        /**
         * 画像ファイルパス image_samune_path
         * @return String
        */
        public function getImagePathAttribute(){
            return $this->prize?->image_path ?? null;
        }


        /**
         * ランクID rank_id
         * @return String
        */
        public function getRankIdAttribute(){
            return $this->prize?->rank_id ?? null;
        }



        /**
         * ストレージ保存された文章（説明文） discription_text
         * @return String
         */
        public function getDiscriptionTextAttribute(){
            return $this->prize?->discription_text;
        }



        /**
         * 説明文モーダルアイコン discription_icon_path
         */
        public function getDiscriptionIconPathAttribute()
        {
            return $this->prize?->discription_icon_path;
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
         */
        public function getREditAttribute()
        {
            return route('admin.gacha_title.title_prize.edit', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'title_prize' => $this->id,
            ]);
        }

        /**
         * [ルーティング]更新 r_update
         */
        public function getRUpdateAttribute()
        {
            return route('admin.gacha_title.title_prize.update', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'title_prize' => $this->id,
            ]);
        }

        /**
         * [ルーティング]削除 r_destroy
         */
        public function getRDestroyAttribute()
        {
            return route('admin.gacha_title.title_prize.destroy', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'title_prize' => $this->id,
            ]);
        }

        /**
         * [ルーティング]コピー r_copy
         */
        public function getRCopyAttribute()
        {
            return route('admin.gacha_title.title_prize.copy', [
                'gacha_title' => $this->manuf_gacha_title_id,
                'title_prize' => $this->id,
            ]);
        }


}


