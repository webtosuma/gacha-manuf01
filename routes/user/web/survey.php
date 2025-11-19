<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| アンケート
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','user_rank'])->group(function () {

    # 回答ページ
    Route::post('survey/answering/{category_code}/{gacha}/{key}',
    [Controllers\SurveyController::class, 'answering'])
    ->name('survey.answering');

    # 戻るページ用
    Route::get('survey/answering/{category_code}/{gacha}/{key}',
    [Controllers\SurveyController::class, 'answering'])
    ->name('survey.answering.get');

});


/*
|--------------------------------------------------------------------------
| アンケート API
|--------------------------------------------------------------------------
*/
    # 詳細
    Route::post('survey/api/show/{survey}',
    [Controllers\SurveyController::class, 'api_show'])
    ->name('survey.api.show');
