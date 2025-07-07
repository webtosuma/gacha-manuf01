@extends('admin.layouts.app')


@section('title','ECストアー商品 ガチャ商品を商品登録')


@section('meta') @php
$active_key = 'store_item';
$active_submenu = true;
@endphp @endsection

@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.store_item') }}"
                    >{{ 'ECストアー商品' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ガチャ商品を商品登録</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">ECストアー商品 ガチャ商品を商品登録</h2>

        <a href="{{ route('admin.store_item') }}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <form action="{{route('admin.store_item.prize.store')}}" method="post"
        novalidate
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">

            @csrf

            <div class="fs-4">登録するガチャ用商品を選択してください。</div>
            <a-store-item-prize-list
            token="{{ csrf_token() }}"
            r_api_prize   ="{{ route('admin.api.store_item.prize') }}"
            r_api_category="{{ route('admin.api.gacha.category') }}"
            category_id   ="{{$category_id}}"
            ></a-store-item-prize-list>
        </form>
    </div>
@endsection
