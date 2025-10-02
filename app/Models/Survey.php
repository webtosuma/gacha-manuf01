<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  アンケート　モデル
| =============================================
*/
class Survey extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'code'    ,// コード
        'title'   ,// タイトル
        'resume'  ,// 説明文
        'gacha_id',//リレーション
    ];



    /**　アクセサーをJSONに含める　*/
    protected $appends = [
        'resume_text',                //ストレージ保存された文章を含む'説明文'

        'r_admin_edit',               // [ルーティング]編集
        'r_admin_copy',               // [ルーティング]コピー
        'r_admin_destroy',            // [ルーティング]削除
        'r_admin_api_update',         // [ルーティングAPI]更新
        'r_admin_api_question_create',//[ルーティングAPI]問い新規作成
        'r_admin_api_question_order', //[ルーティングAPI]問い並び替え
    ];



    /** 型を指定 */
    protected $casts = [];



    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =8;
        while ( !$code ) {
            $str = 'sur_'.Str::random($n);
            $model = self::where('code', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Gachaモデル( ガチャ ) リレーション
         * @return \App\Models\Gacha
        */
        public function gacha(){
            return $this->belongsTo(Gacha::class);
        }



        /**
         * SurveyQuestionモデル( アンケート・質問 ) リレーション
         * @return \App\Models\SurveyQuestion
        */
        public function survey_questions(){
            return $this->hasMany(SurveyQuestion::class);
        }



        /**
         * SurveyAnsモデル( アンケートの回答 ) リレーション
         * @return \App\Models\SurveyAns
        */
        public function survey_ans(){
            return $this->hasMany(SurveyAns::class);
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ストレージ保存された文章を含む'説明文' resume_text
         * @return String
         */
        public function getResumeTextAttribute()
        {
            $text = $this->resume;
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
         * [ルーティング]編集 r_admin_edit
         * @return String
        */
        public function getRAdminEditAttribute() { return route('admin.survey.edit',$this->id); }

        /**
         * [ルーティング]コピー r_admin_copy
         * @return String
        */
        public function getRAdminCopyAttribute() { return route('admin.survey.copy',$this->id); }

        /**
         * [ルーティング]削除 r_admin_destroy
         * @return String
        */
        public function getRAdminDestroyAttribute() { return route('admin.survey.destroy',$this->id); }

        /**
         * [ルーティングAPI]更新 r_admin_api_update
         * @return String
        */
        public function getRAdminApiUpdateAttribute() { return route('admin.api.survey.update',$this->id); }


        /**
         * [ルーティングAPI]問い新規作成 r_admin_api_question_create
         * @return String
        */
        public function getRAdminApiQuestionCreateAttribute() { return route('admin.api.survey.question.create'); }

        /**
         * [ルーティングAPI]問い並び替え r_admin_api_question_order
         * @return String
        */
        public function getRAdminApiQuestionOrderAttribute() { return route('admin.api.survey.question.order'); }


    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */

    /* ~ */
}
