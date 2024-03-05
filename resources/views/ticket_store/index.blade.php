{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','チケット交換')


@section('style')
<style>
    .ratio-3x4{ --bs-aspect-ratio: 133.3%; }
</style>
@endsection


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">チケット交換</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">チケット交換</h3>

    <div class="row gx-4 gy-4">
        @forelse ($stores as $item)
            <div class="col-12 col-md-2">
                <a href="{{route('ticket_store.show', $item)}}" class="d-block text-dark btn border-0
                @if($item->count<1) disabled @endif">

                    <div class="row g-3">
                        <div class="col-4 col-md-12">
                            <div class="position-relative pt-0">
                                <!--loading-->
                                <div class="ratio ratio-3x4">
                                    <div class="d-flex align-items-center justify-content-center"
                                    style="z-index:0;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <!--prize image-->
                                <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="z-index:0;">
                                    <ratio-image-component
                                    url="{{ $item->prize->image_path }}" style_class="ratio ratio-3x4 rounded-3"
                                    ></ratio-image-component>
                                </div>


                                @if ($item->count<1)
                                <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="z-index:3; background: rgba(0, 0, 0, .8);"
                                ><div class="d-flex align-items-center justify-content-center h-100 fs- text-white"
                                >SOLD OUT</div></div>
                                @endif
                            </div>
                        </div>
                        <div class="col text-start" style="font-size:11px;">
                            <div class="d-inline-block bg-danger text-white px-2">SAEL</div>
                            <div class="fw-bold mb-">{{$item->prize->name}}</div>
                            <div>在庫数：{{$item->count}}</div>

                            <div class="mt-2 fw-bold text-success">チケット交換</div>
                            <div class="d-inline-block px-3 border border-success rounded-pill text-success text-center fs-6">{{$item->ticket_count}}枚</div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-secondary">*交換できる商品はありません</div>
        @endforelse
    </div>
</div>


    <!--ボトムメニュー-->
    @if(Auth::check())
        <div class="position-fixed bottom-0 end-0 w-100 py-2 text-white"
        style="z-index:50; background:rgb(0, 0, 0, .9);">
            <div class="container mx-auto" style="max-width:900px;">

                <div class="d-flex align-items-center gap-2">
                    <div class="col fs-5 pe-2">
                        <div  style="font-size:14px;">所持チケット：</div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="col-auto">
                                <img src="{{asset('storage/site/image/ticket/success.png')}}"
                                alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem; margin:.2rem 0;">
                            </div>
                            <div class="col">
                                <span class="fs-5 fw-bold">
                                    <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                                </span>
                                <span>枚</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('ticket_sail') }}"
                        class="d-block btn py-1 py-2 border border-success text-success rounded-pill shadow w-100">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="">チケット購入</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('ticket_store') }}"
                        class="d-block btn py-1 btn-danger text-white rounded-pill shadow w-100">
                            <div class="d-flex gap-2 align-items-center">
                                <i class="bi bi-cart4 fs-5"></i>
                                <div class="">商品購入<span class="badge rounded-pill bg-light text-dark ms-1"
                                >{{100}}</span></div>
                            </div>
                        </a>
                    </div>
                </div>



            </div>
        </div>
    @endif

@endsection
