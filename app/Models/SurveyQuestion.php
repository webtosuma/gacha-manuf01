<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  アンケート・質問　モデル
| =============================================
*/
class SurveyQuestion extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'title',//題名
        'body' ,//本文
        'image',//画像
        'type' ,//質問の種類
        'order',//順番
        'survey_id',
    ];



    /**　アクセサーをJSONに含める　*/
    protected $appends = [
        'image_path',//画像ファイルパス
        'body_text', //ストレージ保存された文章を含む'本文'
        'r_admin_api_update', //[ルーティングAPI]更新
        'r_admin_api_destroy',//[ルーティングAPI]削除
    ];



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
         * Surveyモデル( アンケート ) リレーション
         * @return \App\Models\Survey
        */
        public function survey(){
            return $this->belongsTo(Survey::class)
            ->withTrashed(); // 削除を含む
        }



        /**
         * SurveyQuestionOptionモデル( アンケート・質問・選択肢 ) リレーション
         * @return \App\Models\SurveyQuestionOption
        */
        public function survey_question_options(){
            return $this->belongsTo(SurveyQuestionOption::class);
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
        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->prize ? $this->prize->image_path : self::pointImage();
        }



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



    /*
    |--------------------------------------------------------------------------
    | アクセサー ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティングAPI]更新 r_admin_api_update
         * @return String
        */
        public function getRAdminApiUpdateAttribute() { return route('admin.api.survey.question.update',$this->id); }

        /**
         * [ルーティングAPI]削除 r_admin_api_destroy
         * @return String
        */
        public function getRAdminApiDestroyAttribute() { return route('admin.api.survey.question.destroy',$this->id); }



    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */

    /* ~ */
}
