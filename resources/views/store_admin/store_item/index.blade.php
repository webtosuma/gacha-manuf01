@extends('admin.layouts.app')


@section('title','ECストアー商品')


@section('meta') @php
$active_key = 'store_item';
$active_store_menu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ECストアー商品</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">ECストアー商品</h2>


        <a-store-item-list
        token="{{ csrf_token() }}"
        r_api_list    ="{{ route('admin.api.store_item') }}"
        r_api_update  ="{{ route('admin.api.store_item.update') }}"
        r_create      ="{{ route('admin.store_item.create') }}"
        r_prize_create="{{ $r_prize_create }}"
        category_id   ="{{ $category_id }}"
        ></a-store-item-list>



    </div>
@endsection
