<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  サイト内テキスト保存 モデル
| =============================================
*/
class Text extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用
    public $timestamps = true;


    protected $fillable = [
        'type',          //種類
        'body',          //本文
        'enactmented_at',//制定日
    ];

    /** Carbonオブジェクトとして利用 */
    protected $casts = [
        'enactmented_a'  => 'date'
    ];

    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [
        'body_text',   //ストレージ保存された文章（本文）
    ];


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ストレージ保存された文章（本文） body_text　
         * @return String
         */
        public function getBodyTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->body;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }
}
