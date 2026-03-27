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
        'order',
        'published_at',
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
        'image_path',//画像ファイルパス
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
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * カテゴリー category
         * @return int|null
         */
        public function getCategoryAttribute(){
            return $this->prize->category ?? null;
        }


        /**
         * 商品コード code
         * @return string|null
         */
        public function getCodeAttribute(){
            return $this->prize->code ?? null;
        }


        /**
         * 名前 name
         * @return string|null
         */
        public function getNameAttribute(){
            return $this->prize->name ?? null;
        }


        /**
         * 画像ファイルパス image_samune_path
         * @return String
        */
        public function getImagePathAttribute(){
            return $this->prize->image_path ?? null;
        }


        /**
         * ストレージ保存された文章（説明文） discription_text
         * @return String
         */
        public function getDiscriptionTextAttribute(){
            return $this->prize->discription_text;
        }



        /**
         * 説明文モーダルアイコン discription_icon_path
         */
        public function getDiscriptionIconPathAttribute()
        {
            return $this->prize->discription_icon_path;
        }
}


