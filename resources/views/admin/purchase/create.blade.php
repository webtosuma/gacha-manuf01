@extends('admin.layouts.app')


@section('title','新規登録')


@section('meta') @php
$active_key = 'purchase';
$active_submenu = !config('store.admin');
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
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
                <li class="breadcrumb-item"><a href="{{ route('admin.purchase') }}"
                >{{ 'クーポン管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">新規登録</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">新規登録</h2>



        <a href="{{route('admin.purchase')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <form action="{{route('admin.purchase.store')}}" method="post"
        novalidate
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">

            @csrf

            <div class="fs-4">登録するガチャ用商品を選択してください。</div>
            <a-purchase-prize-list
            token="{{ csrf_token() }}"
            r_api_prize   ="{{ route('admin.api.purchase.prize') }}"
            r_api_category="{{ route('admin.api.gacha.category') }}"
            category_id   ="{{$category_id}}"
            ></a-purchase-prize-list>
        </form>


    </div>
@endsection
