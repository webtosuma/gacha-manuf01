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



    <title>@yield('title') - {{ config('app.name') }}</title>


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
        a{ text-decoration: none; }
        th,td{ background-color: #fff !important; }
        /* サイトデフォルト背景 */
        body{
            background: no-repeat center center / cover fixed;
            background-image: url({{asset('storage/site/image/bg01.jpg')}});
        }
        main{
            padding-top: 4.2rem;
            min-height: 80vh;
        }
    </style>

    @yield('style')

    <!-- Googleタグ -->
    @include('includes.google_tag')


</head>
<body class="">
    <div id="app">


        <!--header-->
        <div class="d-none d-md-block">
            @include('includes.header')
        </div>

        <header class="d-md-none position-absolute w-100" style="z-index:100;">
            <div class="row align-items-center p-3 bg-white border-bottom mx-0 px-0">
                <!--戻る-->
                <div class="col-auto">
                    <button class="btn p-0 px-2 borderrr rounded-pill"
                    type="button" onclick="history.back()"
                    ><i class="bi bi-arrow-left fs-4"></i></button>
                </div>
                <!--title-->
                <div class="col text-center">
                    <h2 class="m-0 fs-6 fw-bold">@yield('title')</h2>
                    @if ( config('app.debug') )
                        <h6 class="text-danger m-0 mt-2">TEST MODE</h6>
                    @endif
                </div>
                <!--menu-->
                <div class="col-auto">
                    <button class="btn p-0 px-2 borderrr rounded-pill
                    @if(!Auth::check()) invisible @endif" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                    aria-controls="offcanvasHumberge">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                </div>
            </div>
        </header>
        @if(Auth::check())
            @include('includes.offcanvas_menu')
        @endif


        @if ( isset( $message ) )
            <section class="bg-dark text-warning text-center">{{$message}}</section>
        @endif

        <main>
            @yield('content')
        </main>


        <!--footer-->
        <div class="d-none d-md-block">
            @include('includes.footer')
        </div>


        <!-- フェードインアラート -->
        @include('includes.fadein-alert')

    </div>



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/20240214app.js') }}" defer></script> --}}

    <script src="{{ asset('js/animation.js') }}"></script>

    @yield('script')

</body>
</html>
