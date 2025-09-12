@extends('admin.layouts.app')


@section('title','買取表管理')


@section('meta') @php
$active_key = 'purchase';
$active_submenu = !config('store.admin');
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">買取表管理</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">買取表管理</h2>


        <a-purchase-list
        token="{{ csrf_token() }}"
        r_api_list    ="{{ route('admin.api.store_item') }}"
        r_api_update  ="{{ route('admin.api.store_item.update') }}"
        r_create      ="{{ route('admin.store_item.create') }}"
        r_prize_create="{{ $r_prize_create }}"
        category_id   ="{{ $category_id }}"
        ></a-purchase-list>


    </div>
@endsection
