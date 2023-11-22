@extends('admin.layouts.app')


@section('title','ガチャ詳細説明編集')


@section('meta') @php
$active_key = 'gacha';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha.show',$gacha) }}"
                >{{ $gacha->name }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">詳細説明編集</li>
                </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">ガチャ編集</h2>

        <!--タブメニュ-->
        @php $tab='admin.gacha.discription.edit'; @endphp
        @include('admin.gacha.common.tab')


        <div class="row mx-0 g-3">
            <!--flex-c2-->
            <div class="d-none d-lg-block col-8 bg-white bg_gacha rounded-3">

                <!--プレビュー-->
                <div class="col-10 mx-auto">
                    @include('gacha.show_body')
                </div>

            </div>
            <!--flex-c1-->
            <aside class="col ">
                <div class="position-sticky" style="top: 2rem; ">

                    <div class="p-3 bg-light rounded-3 mb-3 overflow-auto" style="max-height:90vh;">

                        <div class="mb-3">
                            <label class="form-label fs-6">1等賞</label>
                            <input class="form-control bg-white mb-2" type="file" id="formFileMultiple" multiple>
                            <textarea class="form-control bg-white"rows="3" placeholder="＊商品の補足説明などがあれば、入力してください。"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-6">2等賞</label>
                            <input class="form-control bg-white mb-2" type="file" id="formFileMultiple" multiple>
                            <textarea class="form-control bg-white"rows="3" placeholder="＊商品の補足説明などがあれば、入力してください。"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-6">3等賞</label>
                            <input class="form-control bg-white mb-2" type="file" id="formFileMultiple" multiple>
                            <textarea class="form-control bg-white"rows="3" placeholder="＊商品の補足説明などがあれば、入力してください。"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-6">4等賞</label>
                            <input class="form-control bg-white mb-2" type="file" id="formFileMultiple" multiple>
                            <textarea class="form-control bg-white"rows="3" placeholder="＊商品の補足説明などがあれば、入力してください。"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-6">ラストワン賞</label>
                            <input class="form-control bg-white mb-2" type="file" id="formFileMultiple" multiple>
                            <textarea class="form-control bg-white"rows="3" placeholder="＊商品の補足説明などがあれば、入力してください。"></textarea>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.gacha.discription.edit',$gacha) }}"
                            class="btn btn-warning text-white shadow w-100">更新する</a>
                        </div>
                    </div>



                </div>
            </aside>
        </div>
    </div>
@endsection
