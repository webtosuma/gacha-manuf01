@extends('layouts.app')

<!----- title ----->
@section('title',$subscription['label'].'お申し込み完了')


@section('script')
    {{--- 紙吹雪　CDN ---}}
    @include('includes.confetti_js')
@endsection


@section('style')
    <style>
        /* カスタム背景 */
        #bgWindow{
            background-image: linear-gradient(to right bottom, #406aff, #14cfa0) !important;
        }

    </style>
@endsection



@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active text-white" aria-current="page">{{$subscription['label'].'お申し込み完了'}}</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="text-center text-white my-3">
        <div class="">『{{$subscription['label']}}』</div>
        <div class="">のお申し込みが完了しました</div>
    </h3>


    <ul class="list-group list-group-flush mb-5 mx-auto" style="max-width:600px;">
        <li class="list-group-item bg-white py-">
            <div class="row gy-3 my-3 mx-auto" style="max-width:400px;">
                <div class="col-6">ポイント数</div>
                <div class="col-6 border-bottom fw-bold text-center">
                    <div class="fs-5">{{ number_format($subscription['point_value']).'pt' }}</div>
                </div>


                <div class="col-6">チケット数</div>
                <div class="col-6 border-bottom fw-bold text-center">
                    <div class="fs-5">{{ number_format($subscription['ticket_value']).'枚' }}</div>
                </div>

                <div class="col-6">支払い金額</div>
                <div class="col-6 border-bottom fw-bold text-center">{{ number_format($subscription['price']).'円（税込）' }}</div>

            </div>


            <div class="row gy-3 my-3 mx-auto mt-4 align-items-end" style="max-width:400px;">
                <div class="col-6">現在の所有ポイント</div>
                <div class="col-6 border-bottom fw-bold text-center fs-5 text-warning">{{ number_format(Auth::user()->point).'pt' }}</div>
                <div class="col-6">現在の所有チケット</div>
                <div class="col-6 border-bottom fw-bold text-center fs-5 text-success">{{ number_format(Auth::user()->ticket).'枚' }}</div>
            </div>
        </li>
    </ul>




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
