<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminSurveyRequest;
use App\Models\Survey;
use App\Models\SurveyQuestion;
/*
| =============================================
|  Admin API　アンケート コントローラー
| =============================================
*/
class AdminApiSurveyController extends Controller
{
    /**
     * 一覧
     *
     * @param Request $request
     * @return JSON
    */
    public function index(Request $request)
    {
        # アンケート情報
        $query = Survey::query();

            # キーワード(タイトル・コード)検索
            // $query->keyWordSearch($request);

            # フォルダ分け

            # ガチャ紐付きの有無

            # 並び順
            $query->orderByDesc('created_at')->orderByDesc('id');

        $surveys = $query->paginate(20);

        # [ルーティング]新規登録
        $r_create = route('admin.survey.create');

        # 入力情報
        $inputs = $request->all();


        # JSONを返す
        return response()->json( compact(
            'surveys','r_create','inputs'
        ));
    }




    /**
     * 詳細
     *
     * @param Request $request
     * @param Survey $survey
     * @return JSON
    */
    public function show(Request $request, Survey $survey)
    {
        # 新規登録データ
        // $new_survey = new Survey(['id'=>0, 'title'=>'タイトル', 'resume'=>'説明文',]);
        $new_survey = new Survey(['id'=>0, 'title'=>'', 'resume'=>'',]);
        $survey = $survey->id ? $survey : $new_survey ;

        # 問い情報
        $questions = $survey->questions;

        # 新規登録用　問い
        $new_question = new SurveyQuestion(['body'=>'hoge', 'type'=>'text', 'survey_id'=>$survey->id]);

        # [セレクト]問いの種類
        $select_question_types = SurveyQuestion::types();

        # 入力情報
        $inputs = $request->all();


        # JSONを返す
        return response()->json( compact(
            'survey',
            'questions',
            'new_question',
            'select_question_types',
            'inputs'
        ));
    }




    /**
     * 登録
     *
     * @param AdminSurveyRequest $request
     * @return JSON
    */
    public function post(AdminSurveyRequest $request)
    {
        # 入力情報
        $inputs = self::processingInputs( $request );

        # 新規登録データ
        $survey = new Survey( $inputs );
        $survey->save();

        $inputs = $request->all();

        # JSONを返す
        return response()->json( compact( 'survey', 'inputs' ));
    }




    /**
     * 更新
     *
     * @param AdminSurveyRequest $request
     * @param Survey $survey
     * @return JSON
    */
    public function update(AdminSurveyRequest $request, Survey $survey)
    {
        # 入力情報
        $inputs = self::processingInputs( $request, $survey );

        # 更新
        $survey->update( $inputs );

        $inputs = $request->all();

        # JSONを返す
        return response()->json( compact( 'survey', 'inputs' ));
    }




    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gacha $gacha //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $survey=null )
    {
        $inputs = [];


        # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
            $inputs['title']   = urldecode( $request['encode_title']);
            $inputs['resume']  = urldecode( $request['encode_resume_text']) ;

        # ストレージ更新の処理（本文）body
            $old_text = $survey? $survey->body: null;  //更新前のファイルパステキスト
            $new_text = $inputs['resume'];             //新しい入力テキスト
            $dir = 'upload/survey/resume/';      //保存先ディレクトリ
            $inputs['resume'] = Method::uploadStorageText($dir, $new_text, $old_text);

        # コードの設定(新規登録のとき)
            if($survey==null){
                $inputs['code'] = Survey::CreateCode();
            }


        return $inputs;
    }




}
