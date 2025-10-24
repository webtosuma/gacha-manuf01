@extends('admin.layouts.app')


@section('title','発送受付')


@section('meta') @php
$active_key = 'shipped';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">発送受付</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">発送受付</h2>


        <a-shipped-list
        token="{{ csrf_token() }}"
        mount_state_id={{ $state_id }}
        r_api_list="{{ route('admin.api.shipped') }}"
        r_csv     ="{{route('admin.shipped.dl_csv')}}"
        r_update  ="{{ config('app.is_bulk_shipping',false) ? route('admin.shipped.update') : '' }}"
        r_cancell ="{{ route('admin.shipped.cancell') }}"
        ></a-shipped-list>


    </div>
@endsection
