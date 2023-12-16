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
        'user_id',//特定のユーザーに送信か、否か
    ];

    /** Carbonオブジェクトとして利用 */
    protected $dates = [
        'published_at',//公開設定(利用しない->非公開*消さない)
    ];




    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * InfomationIsReadモデル リレーション
         * @return \App\Models\InfomationIsRead
        */
        public function is_reads()
        {
            return $this->hasMany(InfomationIsRead::class,'infomation_id')
            ->orderByDesc('id');
        }



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
         *  ログインユーザーがお知らせを未読か否か　$infomation->is_read
         *  * ログアウト中は全て既読
         *
         * @return Bool
        */
        public function getIsReadAttribute(){

            $is_read = ! Auth::check() ? null :
            InfomationIsRead::where('user_id', Auth::user()->id)
            ->where('infomation_id', $this->id)->first();

            return Auth::check() ? isset( $is_read  ) : true;
        }


    //

}
