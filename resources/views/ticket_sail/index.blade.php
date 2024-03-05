{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','チケット購入')


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">チケット購入</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">チケット購入</h3>



    <ul class="list-group list-group-flush mb-3">

        <li class="list-group-item bg-white py-3 form-text">
            <div class="row py-4">
                <!--所持チケット-->
                <div class="col">
                    <div class="d-flex gap-3 flex-column flex-md-row align-items-center justify-content-md-start justify-content-center">
                        <div class="">所持チケット</div>

                        <div class="d-flex align-items-center gap-2">
                            <div class="">
                                <img src="{{asset('storage/site/image/ticket/success.png')}}"
                                alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem; margin:.2rem 0;">
                            </div>

                            <span class="fs-3 fw-bold">
                                <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                            </span>
                            <span>枚</span>
                        </div>

                        <a href="{{ route('ticket_store') }}"
                        class="d-block btn btn-sm py-1 btn-success text-white rounded-pill shadow">
                            <div class="d-flex gap-2 align-items-center">
                                <i class="bi bi-gift fs- "></i>

                                <div class="">商品と交換</div>
                            </div>
                        </a>
                    </div>
                </div>
                <!--所持ポイント-->
                <div class="col">
                    <div class="d-flex gap-3 flex-column flex-md-row align-items-center justify-content-md-start justify-content-center">
                        <div class="">所持ポイント</div>

                        <div class="d-flex align-items-center gap-2 pb-1">
                            @include('includes.point_icon')

                            <span class="fs-3 fw-bold">
                                <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                            </span>
                            <span>pt</span>
                        </div>

                        <a href="{{ route('point_sail') }}" class="btn btn-sm btn-warning text-white rounded-pill shadow">ポイント購入</a>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                ポイントと交換するチケットの枚数を選択してください
            </div>
        </li>
        {{-- <li class="list-group-item bg-white py-4 form-text">
            <div class="row">
                @foreach ($ticket_sails as $ticket_sail)
                    <div class="col-4">
                        <div class="d-flex flex-column align-items-center">
                            <img src="{{asset('storage/site/image/ticket/success.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem; margin:.2rem 0;">

                            <div class="d-flex align-items-center gap-2">
                                <span>×</span>
                                <h3 class="m-0 fw-bold">
                                    <number-comma-component number="{{ $ticket_sail->value }}"></number-comma-component>
                                </h3>
                                <span>枚</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between border rounded-pill px-2 mt-2">
                                <number-comma-component number="{{ $ticket_sail->point }}"></number-comma-component>
                                <span>pt</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </li> --}}

        {{-- <li class="list-group-item bg-white py-4 border-0"> --}}
            {{-- 所持チケット --}}
            {{-- <div class="">所持チケット：</div>
            <div class="d-flex justify-content-between align-items-center bg-white">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        <div class="">
                            <img src="{{asset('storage/site/image/ticket/success.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem; margin:.2rem 0;">
                        </div>

                        <span class="fs-3 fw-bold">
                            <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                        </span>
                        <span>枚</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="">
                        <a href="{{ route('ticket_store') }}"
                        class="d-block btn py-1 btn-success text-white rounded-pill shadow w-100">
                            <div class="d-flex gap-2 align-items-center">
                                <i class="bi bi-gift fs-5 "></i>

                                <div class="">商品と交換</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div> --}}
            {{-- 所持ポイント --}}
            {{-- <div class="">所持ポイント：</div>
            <div class="d-flex justify-content-between align-items-center bg-white">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        @include('includes.point_icon')

                        <span class="fs-3 fw-bold">
                            <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                        </span>
                        <span>pt</span>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('point_sail') }}" class="btn btn- btn-warning text-white rounded-pill shadow px-3">ポイント購入</a>
                </div>
            </div> --}}
        {{-- </li> --}}

    {{-- </ul>
    <ul class="list-group list-group-flush"> --}}


        @foreach ($ticket_sails as $ticket_sail)
            <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">

                <div class="">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{asset('storage/site/image/ticket/success.png')}}"
                        alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem; margin:.2rem 0;">

                        <span>×</span>
                        <h3 class="m-0 fw-bold">
                            <number-comma-component number="{{ $ticket_sail->value }}"></number-comma-component>
                        </h3>
                        <span>枚</span>
                    </div>

                    @if( $ticket_sail->service )
                    <div class="badge bg-danger-subtle rounded-pill fw-bold px-3">
                        <span class="text-danger fw-bold fs-6">{{ '+'.$ticket_sail->service }}</span>
                        <span class="text-danger fw-bold">枚お得！</span>
                    </div>
                    @endif
                </div>

                <!--購入ボタン-->
                <a href=""
                class="btn btn-success text-white rounded-pill shadow py-1 px-2 " style="width:8rem;">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        {{-- <span class="me-3">P</span> --}}
                        @include('includes.point_icon')

                        <h5 class="ms-2 m-0 fw-bold">
                            <number-comma-component number="{{ $ticket_sail->point }}"></number-comma-component>
                        </h5>
                        <span>pt</span>
                    </div>
                </a>
            </div></li>
        @endforeach

    </ul>


    <div class="my-5 p-3">
        <div class="col-md-4 mx-auto my-3">
            <a href="#" onClick="history.back(); return false;"
            class="btn btn-lg btn-light border rounded-pill w-100"
            >戻る</a>
        </div>
    </div>

</div>
@endsection
