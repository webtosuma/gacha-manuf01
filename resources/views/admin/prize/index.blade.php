@extends('admin.layouts.app')


@section('title','商品管理')


@section('meta') @php
$active_key = 'prize';
$active_submenu = true;
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
        r_api_destroy ="{{ route('admin.api.prize.destroy') }}"
        r_api_category="{{ route('admin.api.gacha.category') }}"
        r_create      ="{{ route('admin.prize.create') }}"
        r_edit        ="{{ route('admin.prize.edit') }}"
        ></a-prize-list>

    </div>
@endsection
