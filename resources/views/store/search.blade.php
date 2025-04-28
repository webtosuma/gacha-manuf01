{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','商品一覧')


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
            <li class="breadcrumb-item"><a href="{{ route('store') }}">商品ストアー</a></li>
            <li class="breadcrumb-item active" aria-current="page">商品一覧</li>
            </ol>
        </nav>
    </div>


    <div class="container py-md-4 py-5 mb-5">

        <h3 class="d-none d-md-block mb-3">商品一覧</h3>

        {{-- <a href="{{route('store.search')}}">検索ページ</a> --}}

        <u-ticket-store
        token="{{ csrf_token() }}"
        r_api_list="{{route('api.ticket_store')}}"
        r_api_show="{{route('ticket_store.show')}}"
        src_ticket_image="{{asset('storage/site/image/ticket/success.png')}}"
        ></u-ticket-store>


    </div>


@endsection
