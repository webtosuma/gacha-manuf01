<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
/*
 | ===================================
 |  お問い合わせ　
 | ===================================
 */
class Contact extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [

        'name',      //氏名
        'email',     //メール
        'tell',      //電話番号
        'body',      //本文
        // 'type_text', //お問い合わせの種類
        'responsed'  //対応済みか否か
    ];





    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * ストレージ保存された文章を含む'回答'($contact->storage_body)
     * @return String
     */
    public function getStorageBodyAttribute()
    {
        $text = $this->body;
        $path = str_replace(["\r\n", "\r", "\n"], '', $text);

        return Storage::exists($path) ? Storage::get($path) : $text;
    }


    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 一覧表示用データリスト（data_list)
         * @return Array
        */
        public function scopeDataList( $query )
        {
            # 報告一覧データの取得
            $contacts =  $query->orderBy('created_at','desc')->get();

            # データの加工
            for ($i=0; $i < $contacts->count(); $i++) {

                $contact = $contacts[$i];
                $contact->body_text = $contact->storage_body; //ストレージテキスト

            }

            return $contacts;
        }

}
