@extends('admin.layouts.app')


@section('title',$gacha->name.'履歴')


@section('meta') @php
$active_key = 'gacha';
@endphp @endsection



@section('content')
    <div class="container mb-4">


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

                <li class="breadcrumb-item active" aria-current="page">{{ '履歴' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』履歴</h2>


        <a href="{{route('admin.gacha',$gacha->category->code_name)}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>



        <!--タブメニュ-->
        @php $tab='admin.gacha.history'; @endphp
        @include('admin.gacha.common.tab')



        <div class="row mx-0 g-3">
            <!--flex-c2-->
            <div class="col bg-white bg_gacha rounded-3">

                <!--table-->
                @include('admin.gacha.history.table')



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
