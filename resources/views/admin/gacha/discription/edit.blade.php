@extends('admin.layouts.app')


@section('title','ガチャ詳細説明編集')


@section('meta') @php
$active_key = 'gacha';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


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
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha.show',$gacha) }}"
                >{{ $gacha->name }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">詳細説明編集</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』編集</h2>

        <a href="{{ route('admin.gacha',$gacha->category->code_name) }}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>


        <!--タブメニュ-->
        @php $tab='admin.gacha.discription.edit'; @endphp
        @include('admin.gacha.common.tab')


        <form action="{{ route('admin.gacha.discription.update', $gacha) }}" method="POST"
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf
            @method('PATCH')


            <div class="row g-0">
                <!--flex-c2-->
                <div class="col mb-5">

                    @include('admin.gacha.discription._inputs')

                </div>
                <aside class="col-12 col-md-3 ">
                    <div class="position-sticky p-3" style="top: 2rem; ">
                        <disabled-button style_class="btn btn-warning text-white w-100 shadow"
                        btn_text="更新する"></bdisabled-button>
                    </div>
                </aside>
            </div>


        </form>


    </div>
@endsection
