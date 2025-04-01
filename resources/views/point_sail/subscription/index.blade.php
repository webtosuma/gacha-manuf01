@extends('layouts.app')

<!----- title ----->
@section('title','サブスクプラン')


@section('meta')
    @php
        $meta_title = 'サブスクプラン';
        // $meta_image = asset( 'storage/'.'site/image/campaign_introductory/index.png' );
    @endphp
@endsection

@section('style')
    @php
        /* 背景パス */
        $bg_image = \App\Http\Controllers\AdminBackGroundController::getBgSub();
    @endphp
    <style>
        #bgWindow{
            background-image: url({{ $bg_image }});
            opacity: .5;
        }
    </style>
@endsection


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item "><a href="{{ route('point_sail') }}">ポイント購入</a></li>
            <li class="breadcrumb-item active " aria-current="page">サブスクプラン</li>
            </ol>
        </nav>
    </div>

    {{-- <div class="container py-md-4 mb-5">


        <h3 class="d-none d-md-block">サブスクプラン</h3>


        <h3 class="text-info fw-bold text-center fs-3 mt-3 mb-3">サブスクプラン</h3>

    </div> --}}
    <div class="container text- py-4" style="background: rgba(255, 255, 255, 0">

        <h3 class="fw-bold ">サブスクプラン</h3>

    </div>



    <div class="">
        <div class="container py-5  mx-auto">
            <section class="row gy-5 justify-content-center">

                @foreach ($subscriptions as $subscription_id => $subscription)
                    <div class="col-12 col-md-6 col-lg-4">

                        @include('point_sail.subscription.card')

                    </div>
                @endforeach
            </section>
            <section class="mt-5 py-3 w-100">

                <div class="mx-auto col-8 col-md-4 mb-3">
                    <a href="{{ route('point_sail.customer_portal') }}"
                    class="btn btn-light border rounded-pill w-100"
                    >利用中のプランの確認はこちら</a>
                </div>

                <div class="mx-auto col-8 col-md-4">
                    <a href="{{ route('point_sail') }}"
                    class="btn btn-warning rounded-pill w-100"
                    >ポイント購入に戻る</a>
                </div>

            </section>

            <!--注意事項ー-->
            <section class="my-5 py-">
                <div class="p-3 rounded-4" style="background:rgb(255, 255, 255, .9);">

                    <h6 class="border border-dangerrr border-2 p-2 text-dangerrr text-center">
                        注意事項。
                    </h6>


                    <!--注意事項-->
                    @include('point_sail.subscription.notes')


                </div>
            </section>
        </div>
    </div>
@endsection
