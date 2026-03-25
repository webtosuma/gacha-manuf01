<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')


    <title>@yield('title') - {{ config('app.name') }} サイト管理者ページ</title>



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
<body class="">
    <div id="app">


        <!--header-->
        <div class="d-lg-none">
            @include('manuf_admin.includes.gacha_title.header')
        </div>


        <main class="row mx-0 g-0">


            <!--flex-c1-->
            <aside class="d-none d-lg-block col-auto pe-0 bg-body">
                <div class="position-sticky ps-2" style="top: 0rem; ">



                    <div class="py-1  mb-3 text-center">
                        <a href="{{route('admin.gacha_title')}}" class="btn btn-sm btn-light ">< タイトル一覧に戻る</a>
                    </div>

                    @include('manuf_admin.includes.gacha_title.side_menu')


                </div>
            </aside>
            <!--flex-c2-->
            <div class="col bg-white">

                <div class="border-bottom">
                    {{-- <h2 class="container fs-5 my-2">@yield('title')</h2> --}}
                    <h2 class="container fs-5 my-2">{{ $gacha_title->name }}</h2>
                </div>


                <div class="container px-0 px-md-3" style="min-height:90vh;">


                    @yield('content')


                </div>


                <!-- Footer -->
                <footer class="p-3 bg-white text-center">
                    <small class="d-block mb-3 text-muteddd">&copy;{{config('app.company_name')}}</small>
                </footer>
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
