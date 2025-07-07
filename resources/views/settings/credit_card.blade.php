@extends('layouts.sub_toggl')

<!----- title ----->
@section('title','クレジット情報設定')


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">クレジット情報設定</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3>クレジット情報設定</h3>


    <div class="d-flexx justify-content-center mt-5 mb-3">
        <form action="{{ route('settings.credit_card.create' ) }}" method="post">
            @csrf
            <script
            src="https://checkout.pay.jp/"
            class="payjp-button"
            data-key="{{ config('payjp.public_key') }}"
            data-text="クレジットカード情報の新規登録"
            data-submit-text="新規登録"
            ></script>
        </form>
    </div>


    <div class="my-3">
        @forelse ($cardList as $key => $card)
            <div class="card card-body bg-white mb-3">
                <div class="row">
                    <div class="col">
                        <div class="card-item">
                            <label>
                                <span class="brand">{{ $card['brand'] }}</span>
                                <span class="number">{{ $card['cardNumber'] }}</span>
                            </label>
                            <div>
                                <p class="m-0">名義: {{ $card['name'] }}</p>
                                <p class="m-0">期限: {{ $card['exp_year'] }}/{{ $card['exp_month'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto">
                        <form action="{{ route('settings.credit_card.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="payjp_card_id" value="{{ $card['id'] }}">

                            <button type="submit" class="btn btn-sm btn-light border"
                            >削除</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="mb-3 text-center py-5 text-secondary">
                *登録されているクレジット情報はありません
            </div>
        @endforelse
    </div>
    <div class="my-5">
        <div class="col-md-6 mx-auto my-3">
            <a href="{{ route('settings') }}"
            class="btn btn-lg btn-light border rounded-pill w-100"
            >会員情報設定に戻る</a>
        </div>
    </div>
</div>
@endsection
