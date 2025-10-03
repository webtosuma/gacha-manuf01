<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| アンケート
|--------------------------------------------------------------------------
*/

Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('admin/survey',
    [Controllers\AdminSurveyController::class, 'index'])
    ->name('admin.survey');

    # 詳細 (プレビュー)
    Route::get('admin/survey/show/{code}',
    [Controllers\AdminSurveyController::class, 'show'])
    ->name('admin.survey.show');

    # 回答結果
    Route::get('admin/survey/answer/{code}',
    [Controllers\AdminSurveyController::class, 'answer'])
    ->name('admin.survey.answer');

    # 新規登録
    Route::get('admin/survey/create',
    [Controllers\AdminSurveyController::class, 'create'])
    ->name('admin.survey.create');

    # 編集
    Route::get('admin/survey/edit/{code?}',
    [Controllers\AdminSurveyController::class, 'edit'])
    ->name('admin.survey.edit');

    # コピー
    Route::post('admin/survey/copy/{code}',
    [Controllers\AdminSurveyController::class, 'copy'])
    ->name('admin.survey.copy');

    # 削除
    Route::delete('admin/survey/destroy/{code}',
    [Controllers\AdminSurveyController::class, 'destroy'])
    ->name('admin.survey.destroy');


});


/*
|--------------------------------------------------------------------------
| アンケート API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::post('admin/api/survey',
    [Controllers\AdminApiSurveyController::class, 'index'])
    ->name('admin.api.survey.show');

    # 詳細
    Route::post('admin/api/survey/show/{survey?}',
    [Controllers\AdminApiSurveyController::class, 'show'])
    ->name('admin.api.survey.show');

    # 登録
    Route::post('admin/api/survey/post/',
    [Controllers\AdminApiSurveyController::class, 'post'])
    ->name('admin.api.survey.post');

    # 更新
    Route::patch('admin/api/survey/update/{survey}',
    [Controllers\AdminApiSurveyController::class, 'update'])
    ->name('admin.api.survey.update');

    # 並び替え
    Route::patch('admin/api/survey/order',
    [Controllers\AdminApiSurveyController::class, 'order'])
    ->name('admin.api.survey.order');

});
/*
|--------------------------------------------------------------------------
| アンケート・問い API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 登録
    Route::post('admin/api/survey/question/post',
    [Controllers\AdminApiSurveyQuestionController::class, 'post'])
    ->name('admin.api.survey.question.post');

    # 更新
    Route::patch('admin/api/survey/question/update/{question}',
    [Controllers\AdminApiSurveyQuestionController::class, 'update'])
    ->name('admin.api.survey.question.update');

    # 削除
    Route::delete('admin/api/survey/question/destroy/{question}',
    [Controllers\AdminApiSurveyQuestionController::class, 'destroy'])
    ->name('admin.api.survey.question.destroy');

    # 並び替え
    Route::patch('admin/api/survey/question/order',
    [Controllers\AdminApiSurveyQuestionController::class, 'order'])
    ->name('admin.api.survey.question.order');

});
