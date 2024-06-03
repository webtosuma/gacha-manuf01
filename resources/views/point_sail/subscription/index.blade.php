
@extends('layouts.app')

<!----- title ----->
@section('title','月額プラン-カドフェPASS')


@section('meta')
    @php
        $meta_title = 'ご月額プラン-カドフェPASS';
        // $meta_image = asset( 'storage/'.'site/image/campaign_introductory/index.png' );
    @endphp
@endsection

@section('style')
    <style>
        /* カスタム背景 */
        #bgWindow{
            /* background-image: url({{ asset( 'storage/'.'site/image/campaign_introductory/bg04.jpg' ) }}); */
            background-image: linear-gradient(to right bottom, #406aff, #14cfa0) !important;
        }

    </style>
@endsection


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item text-white"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item text-white"><a href="{{ route('point_sail') }}">ポイント購入</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">月額プラン-カドフェPASS</li>
            </ol>
        </nav>
    </div>


    <div class="container py-4 mx-auto" style="max-width:900px;">


        <h3 class="text-white text-center fs-1 mt-3 mb-3">
            3,500ポイント使い放題<br>
            お得な月額プラン<br class="d-md-none">
            『カドフェPASS』
            {{-- 3,000ポイント以上の購入なら、月額プランがお得！！ --}}
        </h3>

        <div class="mx-auto col-6 mb-3">
            <img src="{{asset('storage/site/image/pass.png')}}"
            alt="{{ 'カドフェPASS' }}" class="w-100 rounded-3 shadoww ">
        </div>

        <section class="text-white text-center fs-5 my-3">
            <div class="">ポイントとチケットをお得に入手！</div>
            <div class="">余ったポイントは、繰り越し利用！</div>
            <div class="">ポイント購入を兼用して利用可能！</div>
        </section>

    </div>
    <div class="bg-white">
        <div class="container py-5  mx-auto">
            <section class="row gy-5 justify-content-center">

                @foreach ($subscriptions as $subscription_id => $subscription)
                    <div class="col-12 col-md-6">
                        <div class="shadow border rounded-4 bg-white p-3 py-5 h-100">

                            <h5 class="fw-bold fs-2 text-primary mb-4 text-center border-bottom border-primary border-2 pb-2"
                            >{{$subscription['label']}}</h5>


                            <div class="d-flex align-items-center justify-content-center">
                                <div class="">


                                    <div class="fs- mb-3">
                                        <i class="bi bi-check-circle-fill text-primary me-1 fs-3"></i>
                                        <span class="fs-5 fw-bold text-primary"
                                        >{{number_format($subscription['point_value'])}}pt</span>
                                        使い放題（500ptお得！）
                                    </div>
                                    <div class="fs- mb-3">
                                        <i class="bi bi-check-circle-fill text-primary me-1 fs-3"></i>
                                        <span class="fs-5 fw-bold text-primary"
                                        >チケット{{number_format($subscription['ticket_value'])}}枚</span>
                                        付与（10枚お得！）
                                    </div>
                                    <div class="fs- mb-3">
                                        <i class="bi bi-check-circle-fill text-primary me-1 fs-3"></i>
                                        <span class="fs-5 fw-bold text-primary">限定ガチャ</span>
                                        解禁！
                                        <div class="">還元率90%以上・ループ系・爆アドが狙える</div>
                                    </div>
                                </div>
                            </div>


                            {{-- @if ( !Auth::check() || (Auth::check() && !Auth::user()->subscription_id) ) --}}
                                <div class="mt-5 mx-auto col-md-8">
                                    <a href="{{route('point_sail.subscription.payment',$subscription_id)}}"
                                    class="btn btn-lg rounded-pill text-white w-100 shadow"
                                    style="background-image: linear-gradient(to right bottom, #ed5565, #ff934c) !important;"
                                    >このプランを申し込む</a>
                                </div>
                            {{-- @endif --}}


                            @if(
                                Auth::check() && Auth::user()->subscription_id == $subscription_id
                                && ! $subscription['destory']
                            )
                                <div class="mt-5 text-center">
                                    <div class="text-success mb-2 fw-bold">プラン契約中</div>
                                    <a href="{{route('point_sail.subscription.destroy',$subscription_id)}}"
                                    class="btn btn- rounded-pill text-white mx-auto btn-danger shadow"
                                    >このプランを解約する</a>
                                    <div class="mt-2">*プランを解約すると、次回からさサービスの提供と支払いが停止されます。</div>
                                </div>
                            @endif


                            @if(
                                Auth::check() && Auth::user()->subscription_id == $subscription_id
                                && $subscription['destory']
                            )
                                <div class="mt-5 text-center">

                                    <div class="text-danger mb-2 fw-bold">プラン解約申請済み</div>
                                    <div class="form-text">{{$subscription['destory_at']->format('Y年m月d日まで再契約できません。')}}</div>

                                </div>
                            @endif


                            {{-- <div class="mt-5 text-center">
                                <div class="text-danger mb-2">解約予約が設定されています。</div>
                                <a href=""
                                class="btn btn- rounded-pill text-white mx-auto btn-primary shadow"
                                >このプランの解約をキャンセルする</a>
                                <div class="mt-2">*解約をキャンセルすると、引き続きこの月額プランを自動更新します。</div>
                            </div> --}}

                        </div>
                    </div>
                @endforeach
            </section>
            <section class="mt-5 py- w-100">


                <div class="mx-auto col-6 col-md-4">
                    <a href="{{ route('point_sail') }}"
                    class="btn btn-outline-warning rounded-pill w-100"
                    >ポイント購入はこちら</a>
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
