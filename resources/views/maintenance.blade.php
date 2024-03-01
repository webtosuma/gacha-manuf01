<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- meta -->
    @yield('meta')
    @include('includes.meta')



    <title>メンテナンス中 - {{ config('app.name') }}</title>


    <!-- wbマニフェスト -->
    @if ( !config('app.debug') )
        <link rel="manifest" href="/manifest.json">
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">
    <!-- bootstrap アイコン の読み込み-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animation.css') }}" rel="stylesheet">
    <style>
        /* サイトデフォルト背景 */
        body{
            background: no-repeat center center / cover fixed;
            background-image: url({{asset('storage/site/image/bg01.jpg')}});
        }
    </style>

</head>
<body class="" >

    <div class="d-flex align-items-center justify-content-center bg-" style="height: 100vh;">
        <div class="text- p-3">


            {{-- <div class="text-center mb-5">
                <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="height:4.4rem;">
            </div> --}}

            <h2 class="mb-3">只今、メンテナンス中です。<br>しばらくお待ちください。</h2>


            <div class="rounded shadow p-3 text-center my-3 bg-light">
                <h5 class="text-danger">メンテナンス予定時間</h5>
                2024年3月4日(月) 14:00~14:30
            </div>

            <p>
                ご不便をおかけしますが、ご理解いただけますようよろしくお願いします。<br>
                お時間を置いて、またお越しくださいませ。
            </p>


            <div class="text-center mt-5">
                <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="height:2.4rem;">
            </div>
            <div class="mt-3 d-flex justify-content-center">
                <a href="https://twitter.com/CardFesta7627" rel="nofollow" target="_blank">
                    <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                    alt="xロゴ" class="d-block p-2" style=" width:2rem; height:2rem;">
                </a>
                <a href="https://note.com/cardfesta" rel="nofollow" target="_blank">
                    <img src="{{asset('storage/site/image/note-logo/main/icon.png')}}"
                    alt="noteロゴ" class="d-block p-" style=" width:2rem; height:2rem;">
                </a>
                <a href="https://www.instagram.com/cardfesta/" rel="nofollow" target="_blank">
                    <img src="{{asset('storage/site/image/instagram-logo/01/gradient.png')}}"
                    alt="インスタグラムロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
                </a>
                <a href="https://www.tiktok.com/@cardfesta" rel="nofollow" target="_blank">
                    <img src="{{asset('storage/site/image/tiktok-icons/black_circle.png')}}"
                    alt="tiktokロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
                </a>
            </div>


        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/animation.js') }}"></script>


</body>
</html>
