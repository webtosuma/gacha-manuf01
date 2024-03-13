@extends('admin.layouts.app')


@section('title','ガチャ動画演出編集')


@section('meta') @php
$active_key = 'gacha';
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

                <li class="breadcrumb-item active" aria-current="page">動画演出編集</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』編集</h2>

        <a href="{{ route('admin.gacha',$gacha->category->code_name) }}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>

        <!--タブメニュ-->
        @php $tab='admin.gacha.movie.edit'; @endphp
        @include('admin.gacha.common.tab')


        <form action="{{ route('admin.gacha.movie.update',$gacha) }}" method="POST"
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf
            @method('PATCH')


            <div class="row g-0">
                <!--flex-c2-->
                <div class="col mb-5">

                    @include('admin.gacha.movie._inputs')

                </div>
                <aside class="d-none d-lg-block col-3 ">
                    <div class="position-sticky p-3" style="top: 2rem; ">
                        <disabled-button style_class="btn btn-warning text-white w-100 shadow"
                        btn_text="更新する"></disabled-button>

                        <a href="{{route('admin.movie')}}" class="btn border mt-3 py-0 w-100" target="_blank"
                        ><i class="bi bi-play-btn fs-4 me-2"></i>動画を確認する</a>
                    </div>
                </aside>
            </div>


        </form>

    </div>
@endsection
