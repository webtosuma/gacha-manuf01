<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  ユーザーアドレス テーブル 
| =============================================
*/
class UserAddress extends Model
{
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;


    protected $fillable = [
        'name',       //宛名
        'tell',       //電話番号
        'user_id',    //リレーションID
        'postal_code',//'郵便番号'
        'todohuken' , //'住所-都道府県'
        'shikuchoson',//'住所-市町村'
        'number',     //'住所-番地'
        'is_default', //デフォルトの送信先か否か
        'size',       //靴のサイズ 2024/12/26追加
        'email',      //メールアドレス 2025/12/02追加
        'remarks',    //備考欄 2025/12/02追加,
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserAddressFactory::new();
    }


    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [
        'remarks_text', //ストレージ保存された文章　
    ];


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * ストレージ保存された文章　remarks_text
         * @return String
         */
        public function getRemarksTextAttribute()
        {
            $text = $this->remarks;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }
}
