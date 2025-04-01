@extends('layouts.sub')

<!----- title ----->
@section('title',$subscription->sub_label.'お申し込み完了')


@section('script')
    {{--- 紙吹雪　CDN ---}}
    @include('includes.confetti_js')
@endsection


@section('style') @endsection


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item "><a href="{{ route('point_sail') }}">ポイント購入</a></li>
          <li class="breadcrumb-item "><a href="{{ route('point_sail.subscription') }}">サブスクプラン</a></li>
          <li class="breadcrumb-item active " aria-current="page">{{$subscription->sub_label.'お申し込み完了'}}</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">


    <ul class="list-group list-group-flush mb-5 mx-auto" style="max-width:600px;">
        <li class="list-group-item bg-white py-">
            <h3 class="text-center my-3 fs-5">
                <div class="">『{{$subscription->sub_label}}』</div>
                <div class="">のサブスク契約が完了しました</div>
            </h3>
        </li>
        <li class="list-group-item bg-white py-">
            <div class="row gy-3 my-3 mx-auto" style="max-width:400px;">
                <div class="col-6">ポイント数</div>
                <div class="col-6 border-bottom fw-bold text-center">
                    <div class="fs-5">{{ number_format($subscription->value).'pt' }}</div>
                </div>


                {{-- <div class="col-6">チケット数</div>
                <div class="col-6 border-bottom fw-bold text-center">
                    <div class="fs-5">{{ number_format($subscription['ticket_value']).'枚' }}</div>
                </div> --}}

                <div class="col-6">支払い金額</div>
                <div class="col-6 border-bottom fw-bold text-center">{{ number_format($subscription['price']).'円（税込）' }}</div>

            </div>


            <div class="row gy-3 my-3 mx-auto mt-4 align-items-end" style="max-width:400px;">
                <div class="col-6">現在の所有ポイント</div>
                <div class="col-6 border-bottom fw-bold text-center fs-5 text-warning">{{ number_format(Auth::user()->point).'pt' }}</div>
                {{-- <div class="col-6">現在の所有チケット</div>
                <div class="col-6 border-bottom fw-bold text-center fs-5 text-success">{{ number_format(Auth::user()->ticket).'枚' }}</div> --}}
            </div>
        </li>
    </ul>


    <section class="mt-5 py-3 w-100">

        <div class="mx-auto col-8 col-md-4 mb-3">
            <a href="{{ route('point_sail.customer_portal') }}"
            class="btn btn-light border rounded-pill w-100"
            >利用中のプランの確認はこちら</a>
        </div>

        <div class="mx-auto col-8 col-md-4">
            <a href="{{ route('point_sail.subscription') }}"
            class="btn btn-info text-white rounded-pill w-100"
            >サブスクプラン一覧はこちら</a>
        </div>

    </section>


    @if( $before_gacha)
        <div class="mb- py-5 mx-auto" style="max-width:400px;">

            <h5 class="fw-bold text-center mb-3">前回のガチャを続ける</h5>

            @php $gacha = $before_gacha; @endphp
            <a href="{{$gacha->route}}"
            class="card shadow bg-white border-0
            text-dark text-center overflow-hidden text-decoration-none
            hover_anime" style="border-radius:1rem;">


                <!--image-->
                @include('gacha.common.top_image')

                <!--metter-->
                @include('gacha.common.metter')

            </a>
        </div>
    @endif


    <!--おすすめガチャ-->
    <div class="mb- py-5 mx-auto" style="max-width:400px;">

        @include('gacha.common.result_gachas')

    </div>


</div>
@endsection
