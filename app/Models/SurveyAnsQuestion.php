<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  アンケートの回答・質問別　モデル
| =============================================
*/
class SurveyAnsQuestion extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'body',//本文
        'survey_ans_id',//アンケートの回答
        'survey_question_id',
    ];



    /**　アクセサーをJSONに含める　*/
    protected $appends = [
        'body_text',//ストレージ保存された文章を含む'本文'
    ];



    /** 型を指定 */
    protected $casts = [];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * SurveyAnsモデル(アンケートの回答) リレーション
         * @return \App\Models\SurveyAns
        */
        public function survey_ans(){
            return $this->belongsTo(SurveyAns::class)
            ->withTrashed(); // 削除を含む
        }


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
        /**
         * ストレージ保存された文章を含む'本文' body_text
         * @return String
         */
        public function getBodyTextAttribute()
        {
            $text = $this->body;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }





    /* ~ */
}
