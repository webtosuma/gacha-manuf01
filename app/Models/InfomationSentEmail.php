<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  お知らせ メール送信済み　モデル
| =============================================
*/
class InfomationSentEmail extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'infomation_id',
        'user_id' ,
    ];


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * USERモデル リレーション
         * @return \App\Models\User
        */
        public function user(){
            return $this->belongsTo(User::class)->withTrashed();
        }

}
