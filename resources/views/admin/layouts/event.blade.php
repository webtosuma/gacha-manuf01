<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')


    <title>@yield('title') - {{ config('app.name') }} イベント用</title>



    <!--共通CSS-->
    @include('includes.css')

    @yield('style')

    <style>
        a{ text-decoration: none; }
        th,td{ background-color: #fff !important; }
        .nav-tabs .nav-link.active{
            background-color: #fff;
        }
    </style>

</head>
@php $class_bg_dark = config('app.bg_dark') ? 'bg-dark text-white m-0' : 'bg-white';  @endphp
<body class="{{ $class_bg_dark }}">
    <div id="app">

        <!--背景画像-->
        <div id="bgWindow"
        class="position-fixed top-0 start-0 w-100 h-100"
        style="z-index: -1;"
        ></div>


        <header class="bg- border-info border- bottom border-0 pozition-fixed">
            @if ( config('app.debug') )
                <h6 class="text-danger text-center m-0 bg- position-fixed w-100"  style="z-index:101;">TEST MODE</h6>
            @endif


            <div class="container">
                <nav class="navbar navbar-expand-lg p-0">
                    <div class="container-fluid">

                    <!--- サイトロゴ -->
                    <a class="navbar-brand  text-primary" href="{{ route('event.gacha') }}">
                        <h1 class="fs-6 m-0 text-center d-flex flex- align-items-center gap-2">
                            <img src="{{asset('storage/site/image/logo.png')}}"
                            alt="{{ config('app.name') }}" class="d-brock" style="height:4rem;">
                        </h1>
                    </a>
                </nav>
            </div>
        </header>



        <main class="row mx-0 g-0">


            <!--flex-c1-->
            {{-- <aside class="d-none d-lg-block col-auto pe-0 bg-body">
                <div class="position-sticky ps-2" style="top: 2rem; ">


                    @include('admin.includes.side_menu')

                </div>
            </aside> --}}
            <!--flex-c2-->
            <div class="col ">

                <div style="min-height:90vh;">
                    @yield('content')
                </div>


                <!-- Footer -->
                <footer class="p-3 bg-white text-center">
                    <small class="d-block mb-3 text-muteddd">&copy;{{config('app.company_name')}}</small>
                </footer>
                {{-- @include('includes.footer') --}}
            </div>
        </main>



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


    @yield('script')

</body>
</html>
