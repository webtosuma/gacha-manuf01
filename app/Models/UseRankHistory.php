<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  会員ランク履歴　モデル
| =============================================
*/
class UseRankHistory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'user_id', //ユーザーID
        'rank_id', //ランクID
        'pt_count',//ポイント消費数
    ];
}
