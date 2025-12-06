<?php

# ストアー
Route::middleware([
    'user_session_validate', //1アカウント1ログイン(セッションIDチェック)
])->group(function () {


    include('admin/web.php');
    include('admin/api.php');

    include('user/web.php');

    
});
