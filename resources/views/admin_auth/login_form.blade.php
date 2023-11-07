<!DOCTYPE html>
<html lang="ja">
<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('meta')


    <title>管理者ログイン</title>


    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">


    <style>
    </style>


</head>
<body class="">
    <header class="mx-auto" style="max-width:600px">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">


                <!--- サイトロゴ -->
                <div class="navbar-brand">

                    <h1 class="m-0  d-flex align-items-center fs-6" style="height: 2.4rem">
                        <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name', 'Laravel') }}" class="d-block h-100">
                        <span class="fw-bold ms-3 text-dark mt-1" style="font-size:.8rem;">サイト管理者</span>
                    </h1>

                </div>

            </div>
        </nav>
    </header>
    <main class="mx-auto" style="max-width:600px">

        <div class="card">
            <h5 class="card-header bg-dark text-white">{{ __('ログイン') }}</h5>

            <div class="card-body">
                <form method="POST" action="{{ route('admin_auth.login') }}">
                    @csrf

                    @if (session('login_error'))
                    <div class="text-danger mb-3 text-center">※{{ session('login_error') }}</div>
                    @endif

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('メールアドレス') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email"
                            value="{{ old('email', session('email') ) }}" required autocomplete="email" autofocus>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('パスワード') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password"
                            value="{{ old('password', session('password') ) }}"  required autocomplete="current-password">
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-dark text-white">
                                {{ __('ログイン') }}
                            </button>

                        </div>
                    </div>

                </form>
            </div>
        </div>


    </main>
    <footer class="mx-auto" style="max-width:600px">
        <p class="m-0 ">&copy; Next Arrow Inc. All Rights Reserved.</p>
    </footer>


    <!-- JavaScript -->
    <script src="{{ asset('js/app.js') }}" defer></script>


</body>
</html>
