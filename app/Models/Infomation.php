<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  お知らせ　モデル
| =============================================
*/
class Infomation extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'title',       //題名
        'body' ,       //本文
        'image',       //画像
        'is_slide',    //スライドの表示有無
        'published_at',//公開日時
        'send_email_at',//メール送信日時
    ];

    /** Carbonオブジェクトとして利用 */
    protected $casts = [
        'published_at'  => 'datetime',//公開設定(利用しない->非公開*消さない)
        'send_email_at' => 'datetime',//メール送信日時
    ];




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  null;
        }

        /**
         * ストレージ保存された文章（本文） $infomation->body_text
         * @return String
         */
        public function getBodyTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->body;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }


        /**
         * 公開中かどうか is_published
         * @return String
        */
        public function getIsPublishedAttribute()
        {
            return $this->published_at && $this->published_at <= now()->format('Y-m-d H:i:s') ;
        }



        /**
         * 公開状態ID published_status_id //2:予約中 1:公開中 0:未公開
         * @return String
        */
        public function getPublishedStatusIdAttribute()
        {
            if( $this->published_at > now()->format('Y-m-d H:i:s') )
            { return 2; }
            else if( $this->published_at )
            { return 1; }
            else
            { return 0; }
        }
    //
}
