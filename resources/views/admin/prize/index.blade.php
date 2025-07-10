@extends('admin.layouts.app')


@section('title','商品管理')


@section('meta') @php
$active_key = 'prize';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">商品管理</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">商品管理</h2>


        <a-prize-list
        token="{{ csrf_token() }}"
        r_api_prize   ="{{ route('admin.api.prize') }}"
        r_api_update  ="{{ route('admin.api.prize.update') }}"
        r_api_copy    ="{{ route('admin.api.prize.copy') }}"
        r_api_destroy ="{{ route('admin.api.prize.destroy') }}"
        r_api_category="{{ route('admin.api.gacha.category') }}"
        r_api_multiple_destroy="{{ route('admin.api.prize.multiple_destroy') }}"

        r_create      ="{{ route('admin.prize.create') }}"
        r_edit        ="{{ route('admin.prize.edit') }}"
        r_download_csv="{{ route('admin.prize.download_csv') }}"
        r_import_csv  ="{{ route('admin.prize.import_csv') }}"
        category_id   ="{{$category_id}}"
        change_ticket ="{{config('u_rank_ticket.change_prize_to_ticket')?1:0}}"
        ></a-prize-list>


    </div>
@endsection
