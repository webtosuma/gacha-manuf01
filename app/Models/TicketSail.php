<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  販売用チケット　モデル
| =============================================
*/
class TicketSail extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'value',       //チケット数
        'point',       //交換するポイント数
        'service',     //サービス差異
        'is_published',//公開設定(利用しない->非公開*消さない)
    ];

}
