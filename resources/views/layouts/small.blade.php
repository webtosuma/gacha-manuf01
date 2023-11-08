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
    <header class="mx-auto" style="max-width:600px;">
        <h1 class="m-0 fs-6">
            <a href="{{route('home')}}" class="navbar-brand  fs-2 fw-bold">
                <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="height:2rem;">
            </a>
        </h1>
    </header>
    <main class="mx-auto p-3" style="max-width:600px;">


        @yield('content')


    </main>
    <footer cclass="mx-auto" style="max-width:600px;">
        {{-- <p class="m-0 text-secondary">&copy; TOSUMA Co.,Ltd.</p> --}}
    </footer>

    <!-- bootstrap JavaScript -->
    @yield('script')

</body>
</html>
