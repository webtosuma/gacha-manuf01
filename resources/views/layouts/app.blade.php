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

    <!--共通CSS-->
    @include('includes.css')

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
@php $class_bg_dark = config('app.bg_dark') ? 'bg-dark text-white m-0' : 'bg-body';  @endphp
<body class="{{ $class_bg_dark }}">
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


        {{-- @if ( isset( $message ) )
            <section class="bg-dark text-warning text-center">{{$message}}</section>
        @endif --}}

        <main>
            @yield('content')
        </main>

        @include('includes.footer')
        {{-- @include('includes.footer_dark') --}}


        <!-- フェードインアラート -->
        @include('includes.fadein-alert')

    </div>



    <!-- Scripts -->
    @include('includes.appjs')

    <!----- animation ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            delay: 100,/*発火までの秒数 (ms)*/
            duration: 1200,/*アニメーション時間 (ms)*/
            once: false,/*発火を1回のみにする*/
            placement:"top-top"/*発火位置:画面中央*/
        });
    </script>

    <script src="{{ asset('js/animation.js') }}"></script>

    @yield('script')

</body>
</html>
