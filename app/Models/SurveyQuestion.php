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
        'body' ,//本文
        'image',//画像
        'type' ,//質問の種類
        'order',//順番
        'survey_id',
    ];



    /**　アクセサーをJSONに含める　*/
    protected $appends = [
        'image_path',         //画像ファイルパス
        'encode_body_text',   //エンコードされたテキスト'本文'
        'body_text',          //ストレージ保存された文章を含む'本文'
        'options_array',
        'r_admin_api_post',   //[ルーティングAPI]登録
        'r_admin_api_update', //[ルーティングAPI]更新
        'r_admin_api_destroy',//[ルーティングAPI]削除
    ];



    /** 型を指定 */
    protected $casts = [ ];


    /** 問の種類 */
    public static function types()
    { return [
        'check'  => '一つ選択',
        'radio'  => '複数選択',
        'select' => 'セレクトボックス(一つ選択)',
        'text'   => 'テキスト入力',
    ]; }



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
        /** 画像なしの時の画像 */
        public static function noImage(){
            return null; //return asset( 'storage/site/image/no_image.jpg' );
        }

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  self::noImage();
        }


        /**
         * エンコードされたテキスト'本文' encode_body_text
         * @return String
         */
        public function getEncodeBodyTextAttribute(){
            return urlencode( $this->body_text );
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



        /**
         * 回答選択肢配列 options_array
         * @return String
         */
        public function getOptionsArrayAttribute(){
            return ['one','twe','tree'];
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティングAPI]登録 r_admin_api_post
         * @return String
        */
        public function getRAdminApiPostAttribute() {
            return $this->id ? route('admin.api.survey.question.post',$this->id) : '' ;
        }

        /**
         * [ルーティングAPI]更新 r_admin_api_update
         * @return String
        */
        public function getRAdminApiUpdateAttribute() {
            return $this->id ? route('admin.api.survey.question.update',$this->id) : '' ;
        }

        /**
         * [ルーティングAPI]削除 r_admin_api_destroy
         * @return String
        */
        public function getRAdminApiDestroyAttribute() {
            return $this->id ? route('admin.api.survey.question.destroy',$this->id) : '' ;
        }



    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */

    /* ~ */
}
