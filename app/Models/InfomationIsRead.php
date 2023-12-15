<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  お知らせ 既読　モデル
| =============================================
*/
class InfomationIsRead extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'infomation_id',
    ];




    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Userモデル リレーション ($infomation_is_read->user)
         * @return User
        */
        public function user(){
            return $this->belongsTo(User::class);
        }


        /**
         * Infomationモデル リレーション ($infomation_is_read->infomation)
         * @return Infomation
        */
        public function infomation(){
            return $this->belongsTo(Infomation::class);
        }

    //
}
