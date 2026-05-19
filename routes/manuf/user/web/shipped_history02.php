<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
==========================================================================
  Manufacturer:ルーティング　発送申請履歴
==========================================================================
*/

Route::middleware([  
  'auth',       /* ログイン必須 */
  'maintenance',//メンテナンス
])->group(function () {

  # 一覧
  Route::get('shipped',
  [Manuf\ShippedController::class, 'index'])
  ->name('shipped');

  # 詳細
  Route::get('shipped/show/{user_shipped}',
  [Manuf\ShippedController::class, 'show'])
  ->name('shipped.show');


  # API 一覧
  Route::post('api/shipped',
  [Manuf\ShippedController::class,'api_index'])
  ->name('shipped.api');

});
