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

    <!--ボトムメニュー-->
    @include('ticket_store.common.bottom_menu')


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

        <div class="row gx-2 gy-4">
            @forelse ($stores as $store)
                <div class="col-4 col-md-3 col-lg-2">
                    <a href="{{route('ticket_store.show', $store)}}" class="d-block text-dark btn border-0 p-0">
                    {{-- @if($store->count<1) disabled @endif"> --}}

                        <!--image-->
                        <div class="position-relative">
                            @include('ticket_store.common.prize_image')

                            <!--登録枚数-->
                            <div class="position-absolute bottom-0 end-0 p-1 d-md-none">
                                <div class="bg-dark text-white px-2 rounded "
                                >{{'×'.$store->count}}</div>
                            </div>
                        </div>

                        <div class="mt-2 d-none d-md-block">
                            <!--discription-->
                            @include('ticket_store.common.prize_discription')
                        </div>
                        <div class="d-md-none bg-white px-3 py-1 shadow-sm mt-1 rounded">
                            <div class="d-flex gap-1 align-items-center justify-content-center mt-" style="font-size:11px;">
                                <img src="{{asset('storage/site/image/ticket/success.png')}}"
                                alt="チケット" class="d-block"  style=" width:16px; height:16px;">
                                {{-- <div class="badge d-inline-block bg-success text-white px-2">チケット交換</div> --}}
                                <i class="bi bi-x"></i>
                                <div class="text-success">
                                    <span class="fs-6">{{$store->ticket_count}}</span>枚
                                </div>
                            </div>
                        </div>


                        {{-- <div class="row g-3">
                            <div class="col-4 col-md-12">

                                <!--image-->
                                @include('ticket_store.common.prize_image')

                            </div>

                            <div class="col">

                                <!--discription-->
                                @include('ticket_store.common.prize_discription')

                            </div>
                        </div> --}}
                    </a>
                </div>
            @empty
                <div class="col-12 text-secondary">*交換できる商品はありません</div>
            @endforelse
        </div>
    </div>


@endsection
