<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  アンケート・質問・選択肢　モデル
| =============================================
*/
class SurveyQuestionOption extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'body' ,//本文
        'order',//順番
        'survey_question_id',
    ];



    /**　アクセサーをJSONに含める　*/
    protected $appends = [ ];



    /** 型を指定 */
    protected $casts = [ ];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * SurveyQuestionモデル(アンケート・質問) リレーション
         * @return \App\Models\SurveyQuestion
        */
        public function survey_question(){
            return $this->belongsTo(SurveyQuestion::class)
            ->withTrashed(); // 削除を含む
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
    /* ~ */
}
