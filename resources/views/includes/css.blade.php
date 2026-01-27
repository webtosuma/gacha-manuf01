<!-- ファビコン画像の読み込み -->
<link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">
<!-- bootstrap アイコン の読み込み-->
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css"> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/animation.css') }}" rel="stylesheet">

<!----- animation ----->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />


<!-- Google Fontsの読み込み -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@900&display=swap" rel="stylesheet">
<style>
    header,main {
        font-family: 'Roboto', sans-serif; /* フォントを適用 */
    }

    /* ガチャ販売機　頭部 */
    .ratio-6x1{ --bs-aspect-ratio:16.67%; }
</style>

<!-- img pass -->
<meta name="img-gacha-mashīn-head" content="{{ asset('storage/site/image/gacha_mashīn/head.png') }}">
<meta name="img-gacha-mashīn-body" content="{{ asset('storage/site/image/gacha_mashīn/body.png') }}">
