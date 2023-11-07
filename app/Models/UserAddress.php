<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  ユーザーアドレス テーブル
| =============================================
*/
class UserAddress extends Model
{
    public $timestamps = true;


    protected $fillable = [
        'postal_code',//'郵便番号'
        'todohuken' , //'住所-都道府県'
        'shikushoson',//'住所-市町村'
        'number',     //'住所-番地'
        'user_id',         //リレーションID
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
}
