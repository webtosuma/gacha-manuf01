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
    Route::get('survey/answering/{code}',
    [Controllers\SurveyController::class, 'answering'])
    ->name('survey.answering');

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
