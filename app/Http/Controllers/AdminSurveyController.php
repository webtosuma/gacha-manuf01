<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
/*
| =============================================
|  Admin　アンケート コントローラー
| =============================================
*/
class AdminSurveyController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $surveys = Survey::orderByDesc('created_at')->get();

        return view('admin.survey.index', compact('surveys'));
    }




    /**
     * 詳細
     *
     * @param  \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     */
    public function show( Survey $survey )
    {
        return view('admin.survey.show', compact('survey'));
    }


    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $survey = new Survey(['id'=>'', 'title'=>'タイトル', 'resume'=>'ホゲホゲ',]);
        // dd($survey->toArray());
        return view('admin.survey.edit', compact('survey'));
    }

    /**
     * 編集
     *
     * @param  String $code
     * @return \Illuminate\Http\Response
     */
    // public function edit(Survey $survey)
    public function edit($code=null)
    {
        $survey = Survey::where('code',$code)->first();
        // dd($survey->toArray());
        // $new_survey = new Survey(['id'=>'', 'title'=>'', 'resume'=>'',]);
        // dd($new_survey->toArray());
        return view('admin.survey.edit', compact('survey'));
    }



}
