@extends('admin.layouts.app')


@section('title','チケット交換用商品管理')


@section('meta') @php
$active_key = 'ticket_store';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">チケット交換用商品管理</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">チケット交換用商品管理</h2>

        <a-ticket-store-list
        token="{{ csrf_token() }}"
        r_api_store   ="{{ route('admin.api.ticket_store') }}"
        r_api_category="{{ route('admin.api.gacha.category') }}"
        r_api_prize   ="{{ route('admin.api.prize') }}"
        r_api_create  ="{{route('admin.api.ticket_store.create')}}"
        r_api_update  ="{{route('admin.api.ticket_store.update')}}"
        r_api_destroy ="{{route('admin.api.ticket_store.destroy')}}"
        category_id   =""
        ></a-ticket-store-list>


        {{-- <a-prize-list
        token="{{ csrf_token() }}"
        r_api_prize   ="{{ route('admin.api.prize') }}"
        r_api_update  ="{{ route('admin.api.prize.update') }}"
        r_api_destroy ="{{ route('admin.api.prize.destroy') }}"
        r_api_category="{{ route('admin.api.gacha.category') }}"
        r_create      ="{{ route('admin.prize.create') }}"
        r_edit        ="{{ route('admin.prize.edit') }}"
        r_download_csv="{{ route('admin.prize.download_csv') }}"
        category_id   =""
        ></a-prize-list> --}}

    </div>
@endsection
