<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminSurveyRequest;
use App\Models\Survey;
/*
| =============================================
|  Admin API　アンケート コントローラー
| =============================================
*/
class AdminApiSurveyController extends Controller
{
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
        $new_survey = new Survey(['id'=>0, 'title'=>'タイトル', 'resume'=>'説明文',]);
        $survey = $survey->id ? $survey : $new_survey ;

        # 問い情報
        $survey_questions = $survey->questions;

        # 入力情報
        $inputs = $request->all();


        # JSONを返す
        return response()->json( compact(
            'survey',
            'survey_questions',
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
