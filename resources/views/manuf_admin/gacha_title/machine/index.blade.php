@extends('manuf_admin.layouts.gacha_title')


@section('title',$gacha_title->name.' 筐体一覧')


@section('meta') @php
$active_key = 'gacha_title.machine';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection



@section('content')


    {{-- パンくずリスト --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
            >{{ 'Top' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title.show',$gacha_title) }}"
            >{{$gacha_title->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'筐体一覧'}}</li>
        </ol>
    </nav>


    <div class="mb-3">
        <a href="{{ route('admin.gacha_title.machine.create',$gacha_title) }}"
        class="btn btn-primary text-white  rounded-pill shadow"
        ><i class="bi bi-plus-lg me-2"></i>新規登録</a>
    </div>


@endsection
