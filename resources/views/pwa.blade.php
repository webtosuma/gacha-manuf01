<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- bootstrap アイコン の読み込み-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

        <!-- ファビコン画像の読み込み -->
        <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- wbマニフェスト -->
        <link rel="manifest" href="/manifest.json">


        <style>
            body{
                background: no-repeat center center / cover fixed;
                background-image: url({{asset('storage/site/image/bg01.jpg')}});
            }
        </style>

    </head>
    <body class="antialiased">

        <div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center gap-3  col-12 col-md-4 mx-auto" style="height:100vh;">
                <h1 class="d-flex align-items-center gap-3 m-0">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock w-100 mb-5">
                </h1>

                <div class="spinner-border text-secondary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="form-text">読み込み中...</div>
                {{-- <a href="https://cardfesta.jp/login"
                class="btn btn-primary text-white w-100 rounded-pill">ログイン/会員登録</a> --}}
            </div>
        </div>






        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- resources/views/layouts/app.blade.php -->
        <script>
            'use strict';

            // 3秒後にリダイレクト
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.location.href = '/';
                }, 3000); // 3000ミリ秒 = 3秒
            });

            if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').then(registration => {
                console.log('Service Worker registered with scope:', registration.scope);
            }).catch(error => {
                console.error('Service Worker registration failed:', error);
            });
            }
        </script>


    </body>
</html>
