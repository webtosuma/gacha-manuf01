{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','チケット交換完了')


@section('meta')

    <!--ヘッダーの戻るボタン-->
    {{-- @php $header_back_btn = true; @endphp --}}

@endsection


@section('style')
<style>
    .ratio-3x4{ --bs-aspect-ratio: 133.3%; }
</style>
@endsection


@section('script')
    {{--- 紙吹雪　CDN ---}}
    @include('includes.confetti_js')
@endsection


@section('content')

    <!--ボトムメニュー-->
    @include('ticket_store.common.bottom_menu')

    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ticket_store') }}">チケット交換</a></li>
            <li class="breadcrumb-item active" aria-current="page">交換完了</li>
            </ol>
        </nav>
    </div>

    <div class="container py-md-4 pb-5 mb-5">
        <div class="col-md-8 card card-body rounded-3 border-0 shadow-sm bg-white text-dark mb-5 mx-auto">

            <h3 class="text-center my-3 mb-5 ">チケット交換が完了しました</h3>

            <div class="row gy-5">
                <div class="col-12 col-md-4">

                    <!--image-->
                    <div class="mx-auto w-50">
                        <ratio-image-component
                        url="{{ $store->prize->image_path }}" style_class="ratio ratio-3x4 rounded-3"
                        ></ratio-image-component>
                    </div>


                </div>
                <div class="col-12 col-md-8">

                    <!--discription-->
                    <div class="d-inline-block bg-success text-white px-2 mb-2">チケット交換</div>
                    <h3 class="fs- fw-bold m-0">{{$store->prize->name}}</h3>
                    <div class="d-inline-block border px-3 bg-whitee text-center mt-1 px-1 rounded-pill">
                        <number-comma-component number="{{$store->point_count}}"></number-comma-component>pt
                    </div>

                    <div class="mt-3">数量：<span class="fs-5">{{$ticket_history->user_prizes->count()}}</span></div>
                    <div class="text-success">
                        <span>交換チケット</span>
                        <span class="fs-3">{{-$ticket_history->value}}</span>枚
                    </div>


                    <div class="d-flex align-items-center gap-2 mt-5 border-top pt-3">
                        <div class="col">残りチケット：</div>
                        <div class="col-auto pe-2">
                            {{-- <div  style="font-size:14px;">所持チケット：</div> --}}
                            <div class="d-flex align-items-center gap-2">
                                <div class="col-auto">
                                    <img src="{{asset('storage/site/image/ticket/success.png')}}"
                                    alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem;">
                                </div>
                                <div class="col">
                                    <span>×</span>
                                    <span class="fs-2 fw-bold">
                                        <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                                    </span>
                                    <span>枚</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- <div class="py-3">
            </div> --}}
        </div>


        <div class="col-md-6 mx-auto">
            <a href="{{ route('ticket_store') }}"
            class="btn btn-lg btn-light rounded-pill border w-100 mb-3"
            >チケット交換一覧に戻る</a>

            <a href="{{ route('user_prize') }}"
            class="btn btn-lg btn-light rounded-pill border w-100 mb-3"
            >取得した商品一覧を見る</a>

        </div>
    </div>
@endsection
