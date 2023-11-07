<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('meta')


    <title>@yield('title') - {{env('APP_NAME')}}</title>

    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">
    <!-- bootstrap アイコン の読み込み-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <!-- bootstrap CSS の読み込み-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    @yield('style')

</head>
<body class="bg-white">
    <header class="container-900 p-0 mb-3">
        <h1 class="m-0">
            {{-- <a href="{{route('top')}}" class="navbar-brand  fs-2 fw-bold"> --}}
                <img src="{{asset('storage/site/image/logo.png')}}" alt="サイトロゴ" class="d-brock" style="height:2rem;">
            {{-- </a> --}}
        </h1>
    </header>
    <main class="container-900" style="min-height: auto;">




        @yield('contents')


    </main>
    <footer class="container-900 text-secondary">
        <p class="m-0 text-secondary">&copy; TOSUMA Co.,Ltd.</p>
    </footer>

    <!-- bootstrap JavaScript -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('script')

</body>
</html>
