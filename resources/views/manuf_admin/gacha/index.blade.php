@extends('manuf_admin.layouts.gacha')


@section('title','ガチャタイトル')


@section('meta') @php
$active_key = 'gacha';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ガチャタイトル</li>
            </ol>
        </nav>



        {{-- <h2 class="mb-5 py-3 border-bottom">ガチャタイトル</h2> --}}



        {{-- @php
        /* ガチャ制限数 */
        $limig_gacha_count = env('LIMIT_GACHA_COUNT');
        @endphp
        @if( $limig_gacha_count )
        <!--公開・公開予約数制限あり-->
        <div class="alert alert-success border-0" role="alert">
            <h6 class="fw-bold text-success">公開ガチャ数の制限あり</h6>
            同じカテゴリー内で<span class="fw-bold">公開・公開予約</span>できるガチャ数は、合わせて<span class="fw-bold">{{$limig_gacha_count}}件</span>以内です。
        </div>
        @endif --}}


        @if( config('gacha.admin.settings') )
            <div class="d-flex gap-3 mb-3">
                <a href="{{route('admin.gacha.settings.edit_list')}}" class="btn btn-light rounded-pill shadow py-1">
                    <div class="d-flex align-items-center gap-2"><i class="bi bi-gear fs-3"></i>設定</div>
                </a>
            </div>
        @endif


        {{-- <section class="mb-5">
            <a-gacha-list
            token        ="{{csrf_token()}}"
            r_api_list   ="{{route('admin.api.gacha')}}"
            r_create     ="{{ route('admin.gacha.create') }}"
            category_code="{{$category_code}}"
            ></a-gacha-list>
        </section> --}}


    </div>
@endsection
