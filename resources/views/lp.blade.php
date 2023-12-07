<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    @php
    $title = "cardFesta（カードフェスタ）|オンラインオリパ・ネットオリパを24時間365日楽しめる！国内送料は無料！ ";
    $description = "オンラインオリパ引くならcardFesta（カードフェスタ）! 高確率、爆アドガチャを多数ご用意しています。ポケカ・ワンピースなど人気オリパを24時間365日楽しめます。国内送料無料で、低コストガチャからハイリスクハイリターンなガチャなど楽しみ方は自由自在！ ";
    $image = asset('storage/site/image/logo00.png');
    @endphp


    <title>{{ $title }}</title>

    <meta  name="description" content="{{ $description }}">
    <meta  name="keywords" content="オンラインオリパ,ネットオリパ,オリパ,ポケカ,ワンピース,カード,ガチャ,トレカ, ">


    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}" />
    <meta property="og:url"         content="https://cardfesta.jp/" />
    <meta property="og:site_name"   content="cardFesta" />
    <meta property="og:image"       content="{{ $image }}" />
    <meta property="og:locale"      content="ja_JP"  />
    <meta property="og:type"        content="website">

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@CardFesta7627" />
    <meta name="twitter:title" content="{{ $title }}" />
    <meta name="twitter:description" content="{{ $description }}" />
    <meta name="twitter:image" content="{{ $image }}" />

    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">
    <!-- bootstrap アイコン の読み込み-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    @include('includes.google_tag')


</head>
<body style="height:100vh;">

    <div class="d-none d-md-flex align-items-center justify-content-center h-100 bg-dark">

        <video class="bg_video"
        loop
        playsinline
        muted
        width="100%"
        poster="{{asset('storage/site/image/yokoku.png')}}"
        >
            {{-- <source src="site/movie/yokoku_2.mp4"></source> --}}
            <source src="{{ asset('storage/site/movie/yokoku_2.mp4') }}"></source>
        </video>


    </div>
    <div class="d-flex d-md-none align-items-center justify-content-center w-100 h-100 bg-dark">

        <video class="bg_video h-100"
        loop
        playsinline
        muted
        width="100%"
        poster="{{asset('storage/site/image/yokoku.png')}}"
        >
            {{-- <source src="site/movie/yokoku_2_mobile.mp4"></source> --}}
            <source src="{{ asset('storage/site/movie/yokoku_2_mobile.mp4') }}"></source>
        </video>


    </div>




    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>

        'use strict';

        // すべてのvideoタグを取得
        const videos = document.querySelectorAll('video');


        // メディアの再生を開始
        for (let index = 0; index < videos.length; index++)
        {
            videos[index].play();
        }

    </script>
</body>
</html>
