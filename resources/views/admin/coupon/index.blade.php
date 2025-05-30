@extends('admin.layouts.app')


@section('title','クーポン管理')


@section('meta') @php
$active_key = 'coupon';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">クーポン管理</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">クーポン管理</h2>


        <a-coupon-list
        token="{{ csrf_token() }}"
        r_api_list   ="{{route('admin.api.coupon')}}"
        r_create     ="{{route('admin.coupon.create')}}"
        r_history    ="{{route('admin.coupon.history')}}"
        card_ration  ="{{config('app.gacha_card_ratio')}}"
        ></a-coupon-list>


    </div>
@endsection
