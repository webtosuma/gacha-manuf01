@extends('admin.layouts.app')


@section('title',$gacha->name)


@section('meta') @php
$active_key = 'gacha';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('style')
<style>
    /* ガチャの背景画像 */
    .bg_gacha{
        background: no-repeat center center / cover fixed;
        background-image: url({{ $gacha->category->bg_image_path }});
    }
</style>
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



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』詳細情報</h2>


        <a href="{{route('admin.gacha',$gacha->category->code_name)}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>



        <!--タブメニュ-->
        @php $tab='admin.gacha.show'; @endphp
        @include('admin.gacha.common.tab')



        <div class="row mx-0 g-3">
            <!--flex-c1-->
            <aside class="col-12 col-md-4 order-md-2">
                <div class="position-sticky" style="top: 2rem; ">

                    @include('admin.gacha.common.data')


                </div>
            </aside>
            <!--flex-c2-->
            <div class="col bg-white bg_gacha rounded-3">

                <!--プレビュー-->
                <div class="col-10 mx-auto pt-4">
                    @include('gacha.show.main')
                </div>

            </div>
        </div>


        <!--Modal-->
        @include('admin.gacha.common.custom_button_modal')


    </div>
@endsection
