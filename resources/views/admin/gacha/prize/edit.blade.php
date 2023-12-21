@extends('admin.layouts.app')


@section('title','ガチャ登録商品編集')


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('meta') @php
$active_key = 'gacha';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha.show',$gacha) }}"
                >{{ $gacha->name }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">登録商品編集</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』編集</h2>

        <a href="{{route('admin.gacha')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>


        <!--タブメニュ-->
        @php $tab='admin.gacha.prize'; @endphp
        @include('admin.gacha.common.tab')


        {{-- {{ $gacha->max_count }} --}}
        <form action="{{ route('admin.gacha.prize.update', $gacha) }}" method="POST"
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf
            @method('PATCH')

            <a-gachaprize-edit
            token="{{ csrf_token() }}"
            category_id   ="{{ $gacha->category->id }}"
            r_api_prize   ="{{ route('admin.api.prize') }}"
            r_api_gacha_ranks ="{{ route('admin.api.gacha.ranks',$gacha) }}"
            r_api_ranks_gacha_prizes ="{{ route('admin.api.gacha.ranks_gacha_prizes') }}"
            ></a-gachaprize-edit>

        </form>


    </div>
@endsection
