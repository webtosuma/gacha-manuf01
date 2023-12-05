<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')


    <title>@yield('title') - {{ config('app.name') }}</title>



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
    </style>

    @yield('style')

    @include('includes.google_tag')


</head>
<body class="">
    <div id="app">
        @include('includes.header')

        @if ( isset( $message ) )
            <section class="bg-dark text-warning text-center">{{$message}}</section>
        @endif

        <main class="py-" style="min-height: 80vh">
            @yield('content')
        </main>

        @include('includes.footer')


        <!-- フェードインアラート -->
        @include('includes.fadein-alert')

    </div>



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{ asset('js/animation.js') }}"></script>

    @yield('script')

</body>
</html>
