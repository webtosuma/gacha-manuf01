@extends('layouts.app')

@section('content')
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
@endsection
