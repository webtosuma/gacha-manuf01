@extends('admin.layouts.app')


@section('title','ガチャ登録商品編集')


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
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha.show',$gacha) }}"
                >{{ $gacha->name }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">登録商品編集</li>
                </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">ガチャ編集</h2>


        <!--タブメニュ-->
        @php $tab='admin.gacha.prize'; @endphp
        @include('admin.gacha.common.tab')


        <section>
        </section>

    </div>
@endsection
