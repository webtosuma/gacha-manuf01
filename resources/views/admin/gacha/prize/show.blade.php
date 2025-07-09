@extends('admin.layouts.app')


@section('title','ガチャ登録商品編集')


@section('meta') @php
$active_key = 'gacha';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-5">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                >{{ 'ガチャ管理' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha',$gacha->category->code_name) }}"
                >{{ $gacha->category->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha.show',$gacha) }}"
                >{{ $gacha->name }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">登録商品一覧</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』登録商品一覧</h2>

        <a href="{{route('admin.gacha',$gacha->category->code_name)}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>

        <!--タブメニュ-->
        @php $tab='admin.gacha.prize.show'; @endphp
        @include('admin.gacha.common.tab')


        <div class="row mx-0 g-3">
            <!--flex-c2-->
            <div class="col bg-white bg_gacha rounded-3">

                <!--プレビュー-->
                @include('admin.gacha.prize.show_list')

            </div>
            <!--flex-c1-->
            <aside class="d-none d-lg-block col-4 ">
                <div class="position-sticky" style="top: 2rem; ">

                    @include('admin.gacha.common.data')

                </div>
            </aside>
        </div>


        <!--Modal-->
        @include('admin.gacha.common.custom_button_modal')

    </div>
@endsection
