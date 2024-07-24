<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('meta')


    <title>@yield('title') - {{env('APP_NAME')}}</title>

    <!-- wbマニフェスト -->
    @if ( !config('app.debug') )
        <link rel="manifest" href="/manifest.json">
    @endif

    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">

    <!-- bootstrap アイコン の読み込み-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <!-- bootstrap CSS の読み込み-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animation.css') }}" rel="stylesheet">
    <style>
        /* サイトデフォルト背景 */
        /* body{
            background: no-repeat center center / cover fixed;
            background-image: url({{asset('storage/site/image/bg01.jpg')}});
        } */
    </style>

    @yield('style')

    @include('includes.google_tag')

</head>
<body class="bg-white">
    <header class="container p-2 mb-3">
        <h1 class="m-0">
            {{-- <a href="{{route('top')}}" class="navbar-brand  fs-2 fw-bold"> --}}
                <img src="{{asset('storage/site/image/logo.png')}}" alt="サイトロゴ" class="d-brock" style="height:2rem;">
            {{-- </a> --}}
        </h1>
    </header>
    <main class="container" style="min-height: auto;">


        @yield('content')


    </main>
    <footer cclass="bg-dark py-3">
        <p class="m-0 text-secondary text-center">&copy;{{config('app.company_name')}}</p>
    </footer>
    @yield('script')

</body>
</html>
