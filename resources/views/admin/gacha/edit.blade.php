@extends('admin.layouts.app')

@section('title','ガチャ基本情報編集')


@section('meta') @php
$active_key = 'gacha';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                >{{ 'ガチャ管理' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha',$gacha->category->code_name) }}"
                >{{ $gacha->category->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha.show',$gacha) }}"
                >{{ $gacha->name }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">基本情報編集</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』編集</h2>

        <a href="{{ route('admin.gacha',$gacha->category->code_name) }}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>


        <!--タブメニュ-->
        @php $tab='admin.gacha.edit'; @endphp
        @include('admin.gacha.common.tab')


        <section>
            <form action="{{ route('admin.gacha.update', $gacha) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')


                @include('admin.gacha._inputs')


            </form>
        </section>

    </div>
@endsection
