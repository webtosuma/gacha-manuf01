<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="image/favicon.png">

    <title>
    cardFesta（カードフェスタ）|オンラインオリパ・ネットオリパを24時間365日楽しめる！国内送料は無料！
    </title>

    <meta  name="description" content=
    "オンラインオリパ引くならcardFesta（カードフェスタ）! 高確率、爆アドガチャを多数ご用意しています。ポケカ・ワンピースなど人気オリパを24時間365日楽しめます。国内送料無料で、低コストガチャからハイリスクハイリターンなガチャなど楽しみ方は自由自在！ "
    >
    <meta  name="keywords" content="オンラインオリパ,ネットオリパ,オリパ,ポケカ,ワンピース,カード,ガチャ,トレカ, ">


    <meta property="og:title" content=
    "cardFesta（カードフェスタ）|オンラインオリパ・ネットオリパを24時間365日楽しめる！国内送料は無料！ "
    />
    <meta property="og:description" content=
    "オンラインオリパ引くならcardFesta（カードフェスタ）! 高確率、爆アドガチャを多数ご用意しています。ポケカ・ワンピースなど人気オリパを24時間365日楽しめます。国内送料無料で、低コストガチャからハイリスクハイリターンなガチャなど楽しみ方は自由自在！ "
    />
    <meta property="og:url"         content="https://cardfesta.jp/" />
    <meta property="og:site_name"   content="cardFesta" />
    <meta property="og:image"       content="image/top.png" />
    <meta property="og:locale"      content="ja_JP"  />
    <meta property="og:type"        content="website">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">


</head>
<body style="height:100vh;">

    <div class="d-none d-md-flex align-items-center justify-content-center h-100 bg-dark">

        <video class="bg_video"
        loop
        playsinline
        muted
        width="100%"
        poster=""
        ><source src="{{ $movie_path['pc'] }}"></source></video>


    </div>
    <div class="d-flex d-md-none align-items-center justify-content-center w-100 bg-dark">

        <video class="bg_video h-100"
        loop
        playsinline
        muted
        width="100%"
        poster=""
        ><source src="{{ $movie_path['mobile'] }}"></source></video>


    </div>




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
