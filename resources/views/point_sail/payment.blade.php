@extends('layouts.small')

<!----- title ----->
@section('title','ポイント購入確認|'.$point_sail->value.'ポイント')


@section('style')
<style>
    #payjp_checkout_box input[type=button] {
        width: 100%;
        padding: .35rem .375rem !important;
        font-size: .9rem  !important;
        border-radius: .375rem !important;
        border: 1px solid #55b5d8 !important;
        color: #fff !important;
        background-color: #55b5d8; !important;
        background-image: none !important;
    }
</style>
@endsection


@section('content')
<h3 class="mt-3">ポイント購入前確認</h3>

<ul class="list-group list-group-flush my-5">
    <li class="list-group-item bg-white py-4">
        <h5 class="fw-bold text-center">ポイント数と金額をご確認ください。</h5>
        <div class="row gy-3 my-3 mx-auto" style="max-width:400px;">
            <div class="col-6">ポイント数</div>
            <div class="col-6 border-bottom fw-bold">
                {{ $point_sail->value }}ポイント
            </div>
            <div class="col-6">支払い方法</div>
            <div class="col-6 border-bottom fw-bold">クレジットカード</div>
            <div class="col-6">支払い金額</div>
            <div class="col-6 border-bottom fw-bold">{{ $point_sail->price.'円（税込）' }}</div>
        </div>
        <div class="">

            <div class="text-danger text-center">
                @if (session('error-message'))
                    <!--エラーメッセージ-->
                    <p>{{ session('error-message') }}</p>
                @endif
            </div>

        </div>
    </li>
    @if (!empty($cardList))
        <li class="list-group-item bg-white py-5">

            <h3 class="text-center fw-bold mb-3">お支払いカード選択</h3>
            <form action="{{ route('point_sail.payment_post',$point_sail) }}" method="post">
                @csrf

                @foreach ($cardList as $key => $card)
                    <div class="card card-body mb-3">
                        <div class="card-item">
                            <label>
                                <input type="radio" name="payjp_card_id" value="{{ $card['id'] }}"
                                @if(!$key) checked @endif />
                                <span class="brand">{{ $card['brand'] }}</span>
                                <span class="number">{{ $card['cardNumber'] }}</span>
                            </label>
                            <div>
                                <p class="m-0">名義: {{ $card['name'] }}</p>
                                <p class="m-0">期限: {{ $card['exp_year'] }}/{{ $card['exp_month'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-8 mx-auto mt-5">
                    <input type="submit" class="btn btn-primary text-white w-100"
                    value="選択したクレジットカードで購入する">
                </div>

            </form>
        </li>
    @endif

    <li class="list-group-item bg-white py-5">
        <h3 class="text-center fw-bold mb-3">お支払いカード新規登録</h3>
        <div class="col-md-8 mx-auto mb-3 mt-5">
            <form action="{{ route('point_sail.create',$point_sail ) }}" method="post">
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
    </li>
    <li class="list-group-item bg-white py-5">
        <div class="col-md-8 mx-auto mt-">
            <a href class="btn btn-light border w-100"
            >購入するポイント数の変更</a>
        </div>
    </li>
</ul>
@endsection
