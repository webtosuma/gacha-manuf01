@extends('admin.layouts.app')


@section('title',$gacha->name)


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
                <li class="breadcrumb-item active" aria-current="page">{{ $gacha->name }}</li>
            </ol>
        </nav>



        {{-- <h2 class="my-5 py-3 border-bottom">{{ $gacha->name }}</h2> --}}


        <div class="row mx-0 g-3">
            <!--flex-c2-->
            <div class="col bg-white bg_gacha rounded-3">

                <!--プレビュー-->
                <div class="col-10 mx-auto">
                    @include('gacha.show.main')
                </div>

            </div>
            <!--flex-c1-->
            <aside class="d-none d-lg-block col-4 ">
                <div class="position-sticky" style="top: 2rem; ">

                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="mb-">
                            <h5 class="border-bottom ">{{ $gacha->name }}</h5>

                            <div class="card-body">
                                <h6 class="">
                                    <div class="d-flex align-items-center gap-2">
                                        @include('includes.point_icon')
                                        <div class="">
                                            1回×
                                            <span class="fs-3">
                                                <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                                            </span>pt
                                        </div>
                                    </div>
                                </h6>
                                <p class="card-text m-0">
                                    残り
                                    <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                                    /
                                    <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                                </p>
                                <div class="progress mb-3">
                                    @php
                                    $ratio = $gacha->remaining_ratio;
                                    $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
                                    $style_class = 'progress-bar progress-bar-striped '.$bg_color
                                    @endphp
                                    <div class="{{ $style_class }}" role="progressbar"
                                    style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"></div>
                                </div>
                            </div>

                            <a href="{{ route('admin.gacha.edit', $gacha) }}" class="btn btn-warning text-white shadow"
                            >基本情報の編集</a>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="mb-">
                            <a href="{{ route('admin.gacha.discription.edit',$gacha) }}"
                            class="btn btn-warning text-white shadow">詳細説明の編集</a>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="mb-">
                            <a href="{{ route('admin.gacha.prize', $gacha) }}"
                            class="btn btn-warning text-white shadow">登録商品の編集</a>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="mb-">
                            <a href="{{ route('admin.gacha.published', $gacha) }}"
                            class="btn btn-warning text-white shadow">公開設定</a>
                        </div>
                    </div>

                </div>
            </aside>
        </div>
    </div>
@endsection
