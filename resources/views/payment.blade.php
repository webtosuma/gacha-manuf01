<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>

                </div>
            </nav>
        </header>

        <main class="py-" style="min-height: 80vh">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ポイント購入</li>
                    </ol>
                </nav>
            </div>
            <div class="container py-4">

                <div class="text-danger">
                    @if (session('error-message'))
                        <!--エラーメッセージ-->
                        <p>{{ session('error-message') }}</p>
                    @endif
                    @if (session('message'))
                        <!--メッセージ-->
                        <p>{{ session('message') }}</p>
                    @endif
                </div>

                <form action="{{ route('payment') }}" method="post">
                    @csrf
                    <script
                    src="https://checkout.pay.jp/"
                    class="payjp-button"
                    data-key="{{ config('payjp.public_key') }}"
                    data-text="クレジットカード情報を入力"
                    data-submit-text="クレジットカードを登録する"
                    ></script>
                </form>

                @if (!empty($cardList))
                    <p>もしくは登録済みのクレジットカードで支払い</p>
                    <form action="{{ route('payment') }}" method="post">
                        @csrf

                        @foreach ($cardList as $key => $card)
                            <div class="card-item">
                                <label>
                                    <input type="radio" name="payjp_card_id" value="{{ $card['id'] }}"
                                    @if(!$key) checked @endif />
                                    <span class="brand">{{ $card['brand'] }}</span>
                                    <span class="number">{{ $card['cardNumber'] }}</span>
                                </label>
                                <div>
                                    <p>名義: {{ $card['name'] }}</p>
                                    <p>期限: {{ $card['exp_year'] }}/{{ $card['exp_month'] }}</p>
                                </div>
                            </div>
                        @endforeach

                        <button type="submit">選択したクレジットカードで決済する</button>
                    </form>
                @endif


            <div class="">key:{{ config('payjp.public_key') }}</div>

        </main>

        {{-- @include('includes.footer') --}}

    </div>
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
</body>
</html>
