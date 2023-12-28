@extends('layouts.app')

<!----- title ----->
@section('title','ポイント購入完了')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">ポイント購入完了</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3>ポイント購入完了</h3>

    <ul class="list-group list-group-flush my-5 mx-auto" style="max-width:600px;">
        <li class="list-group-item bg-white py-4">
            <h3 class="text-center m-0">ポイント購入が完了しました</h3>
        </li>
        <li class="list-group-item bg-white py-4">
            <div class="row gy-3 my-3 mx-auto" style="max-width:400px;">
                <div class="col-6">ポイント数</div>
                <div class="col-6 border-bottom fw-bold">{{ number_format($point_history->value).'ポイント' }}</div>
                <div class="col-6">支払い方法</div>
                <div class="col-6 border-bottom fw-bold">クレジットカード</div>
                <div class="col-6">支払い金額</div>
                <div class="col-6 border-bottom fw-bold">{{ number_format($point_history->price).'円（税込）' }}</div>
            </div>
        </li>
    </ul>

    <div class="mx-auto" style="max-width:600px;">
        <a href="{{ route('gacha_category') }}" class="btn btn-light border w-100"
        >ガチャを続ける</a>
    </div>
</div>
@endsection
