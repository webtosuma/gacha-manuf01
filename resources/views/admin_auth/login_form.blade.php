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
    <header class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">


                <!--- サイトロゴ -->
                <div class="navbar-brand">

                    <h1 class="m-0  d-flex align-items-center fs-6" style="height: 2.4rem">
                        <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name', 'Laravel') }}" class="d-block h-100">
                    </h1>

                </div>

            </div>
        </nav>
    </header>
    <main>
        <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3"
        style="min-height: 80vh; max-width:600px;">

            <form method="POST" action="{{ route('admin_auth.login') }}" class="w-100 text-center">
                @csrf
                <h2 class="h3 mb-3 fw-normal">サイト管理者ログイン</h2>

                @if (session('login_error'))
                    <div class="text-danger mb-3 text-center">※{{ session('login_error') }}</div>
                @endif

                <div class="form-floating mb-3">
                  <input type="email" class="form-control" id="floatingInput" autofocus
                  name="email"
                  value="{{ session('email') ? session('email') : '' }}">
                  <label for="floatingInput">メールアドレス</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="password" class="form-control" id="floatingPassword"
                  name="password"
                  value="{{ session('password') ? session('password') : '' }}">
                  <label for="floatingPassword">パスワード</label>
                </div>

                <div class="col-md- mx-auto mb-3">
                    <button class="w-100 btn btn-lg btn-dark" type="submit">ログイン</button>
                </div>
                <a href="" class="text-decoration-none"
                >パスワードをお忘れの方はこちら</a>
            </form>

            <hr class="my-4 w-100">
            <div class="text-center w-100">
                <small class="text-body-secondary">ユーザーログインはこちら</small>
                <a href="{{ route('login') }}"
                class="w-100 py-2 mb-2 btn btn-primary rounded-3"
                >ユーザーログイン</a>
            </div>

        </div>

    </main>
    {{-- <footer class="container">
        <p class="m-0 ">&copy; Next Arrow Inc. All Rights Reserved.</p>
    </footer> --}}


    <!-- JavaScript -->
    <script src="{{ asset('js/app.js') }}" defer></script>


</body>
</html>
