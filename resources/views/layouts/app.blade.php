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
    @if ( config('app.manifest') )
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
        #bgWindow{
            background: no-repeat center center / cover;
            background-image: url({{asset('storage/site/image/bg00.jpg')}});
        }
        /* body{
            background: no-repeat center center / cover fixed;
            background-image: url({{asset('storage/site/image/bg01.jpg')}});
        } */
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

        <!--背景画像-->
        <div id="bgWindow"
        class="position-fixed top-0 start-0 w-100 h-100"
        style="z-index: -1;"
        ></div>




        @include('includes.header')


        @if(Auth::check())


            @if ( env('NEW_TICKET_SISTEM',false) )
                @include('includes.offcanvas_menu02')
            @else
                @include('includes.offcanvas_menu')
            @endif


        @endif


        @if ( isset( $message ) )
            <section class="bg-dark text-warning text-center">{{$message}}</section>
        @endif

        <main>
            @yield('content')
        </main>

        @include('includes.footer')


        <!-- フェードインアラート -->
        @include('includes.fadein-alert')

    </div>



    <!-- Scripts -->

    @include('includes.appjs')

    <script src="{{ asset('js/animation.js') }}"></script>

    @yield('script')

</body>
</html>
