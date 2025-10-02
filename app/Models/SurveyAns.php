<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  アンケートの回答　モデル
| =============================================
*/
class SurveyAns extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'survey_id',
    ];



    /**　アクセサーをJSONに含める　*/
    protected $appends = [ ];





    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Userモデル リレーション
         * @return \App\Models\User
        */
        public function user(){
            return $this->belongsTo(User::class)
            ->withTrashed(); // 削除を含む
        }


        /**
         * Surveyモデル( アンケート ) リレーション
         * @return \App\Models\Survey
        */
        public function survey(){
            return $this->belongsTo(Survey::class)
            ->withTrashed(); // 削除を含む
        }



        /**
         * SurveyAnsQuestionモデル( アンケートの回答・質問別 ) リレーション
         * @return \App\Models\SurveyAnsQuestion
        */
        public function survey_ans_questions(){
            return $this->hasMany(SurveyAnsQuestion::class);
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
