<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')


    <title>@yield('title') - {{ config('app.name') }} サイト管理者ページ</title>



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


        @include('admin.includes.header')

        <main class="row mx-0 g-0">


            <!--flex-c1-->
            <aside class="d-none d-lg-block col-auto pe-0 bg-body">
                <div class="position-sticky ps-2" style="top: 2rem; ">


                    @include('admin.includes.side_menu')

                </div>
            </aside>
            <!--flex-c2-->
            <div class="col bg-white">

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

    @yield('script')

</body>
</html>
