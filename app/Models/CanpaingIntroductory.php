<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  [キャンペーン]　お友達紹介 rank
| =============================================
*/
class CanpaingIntroductory extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'recruiter_id',//勧誘ユーザーのID
        'friend_id',   //紹介した友達のID
        'done_at',     //キャンペーン付与日
    ];


    /** Carbonオブジェクトとして利用 */
    protected $dates = [
        'done_at',
    ];


}
