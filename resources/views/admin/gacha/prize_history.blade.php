@extends('admin.layouts.app')

<!--title-->
@section('title','「'.$gacha->name.'」の商品履歴')


@section('meta')
    @php
        $meta_title = '「'.$gacha->name.'」の商品履歴';
        // $meta_image = $bg_image;
    @endphp
@endsection



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
                <li class="breadcrumb-item active" aria-current="page">{{ $gacha->name }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '「'.$gacha->name.'」の商品履歴' }}</h2>


        <a href="{{route('admin.gacha',$gacha->category->code_name)}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>



        <!--タブメニュ-->
        @php $tab='admin.gacha.prize_history'; @endphp
        @include('admin.gacha.common.tab')


        <a-gacha-prize-history
        token="{{ csrf_token() }}"
        r_api_list="{{route('gacha.api.prize_history',$gacha)}}"
        ></a-gacha-prize-history>


    </div>
@endsection
