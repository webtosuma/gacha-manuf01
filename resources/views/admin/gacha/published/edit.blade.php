@extends('admin.layouts.app')


@section('title','ガチャ公開設定')


@section('meta') @php
$active_key = 'gacha';
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

                <li class="breadcrumb-item active" aria-current="page">公開設定</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』編集</h2>

        <a href="{{ route('admin.gacha',$gacha->category->code_name) }}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>


        <!--タブメニュ-->
        @php $tab='admin.gacha.published'; @endphp
        @include('admin.gacha.common.tab')


        <form action="{{ route('admin.gacha.published.update', $gacha) }}"
        method="POST" onsubmit="stopOnbeforeunload()" novalidate>
            @csrf
            @method('PATCH')


            <div class="row g-0">
                <!--flex-c2-->
                <div class="col">

                    @include('admin.gacha.published._inputs')

                </div>
                <aside class="col-12 col-md-3 ">

                    @if( env('LIMIT_GACHA_COUNT') )
                    <!--公開・公開予約数制限あり-->
                    <div class="alert alert-success border-0 mx-3" role="alert">
                        <h6 class="fw-bold text-success">公開ガチャ数の制限あり</h6>
                        同じカテゴリー内で<span class="fw-bold">公開・公開予約</span>できるガチャ数は、合わせて<span class="fw-bold">3件</span>以内です。
                    </div>
                    @endif


                    <div class="position-sticky p-3" style="top: 2rem; ">
                        <disabled-button style_class="btn btn-warning text-white w-100 shadow"
                        btn_text="更新する"></bdisabled-button>
                    </div>
                </aside>
            </div>


        </form>


    </div>
@endsection
