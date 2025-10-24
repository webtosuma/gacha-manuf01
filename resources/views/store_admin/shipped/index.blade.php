@extends('admin.layouts.app')


@section('title','EC発送受付')


@section('meta') @php
$active_key = 'store_shipped';
$active_store_menu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">EC発送受付</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">EC発送受付</h2>

        <a-store-shipped-list
        token="{{ csrf_token() }}"
        r_api_list="{{ route('admin.api.store.shipped') }}"
        r_csv     ="{{route('admin.store.shipped.waiting.dl_csv')}}"
        r_update  ="{{ config('app.is_bulk_shipping',false) ? route('admin.store.shipped.waiting.update') : '' }}"
        ></a-store-shipped-list>

    </div>
@endsection
