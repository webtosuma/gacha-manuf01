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


    <div class="container py-md-4 py-5 mb-5">

        <h3 class="d-none d-md-block mb-3">チケット交換</h3>


        <u-ticket-store
        token="{{ csrf_token() }}"
        r_api_list="{{route('api.ticket_store')}}"
        r_api_show="{{route('ticket_store.show')}}"
        src_ticket_image="{{asset('storage/site/image/ticket/success.png')}}"
        ></u-ticket-store>





        {{-- <div class="row gx-2 gy-4">
            @forelse ($stores as $store)
                <div class="col-4 col-md-3 col-lg-2">
                    <a href="{{route('ticket_store.show', $store)}}" class="d-block text-dark btn border-0 p-0">
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
                                <i class="bi bi-x"></i>
                                <div class="text-success">
                                    <span class="fs-6">{{$store->ticket_count}}</span>枚
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-secondary">*交換できる商品はありません</div>
            @endforelse
        </div> --}}
    </div>


@endsection
