<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  チケット交換履歴　モデル
| =============================================
*/
class TicketHistory extends Model
{
    use HasFactory;

    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'user_id', //ユーザーID
        'value',     //チケット数
        'reason_id', //入出理由ID
        'point_history_id',//ポイント収支履歴リレーション
    ];
}
