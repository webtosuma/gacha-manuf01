@extends('admin.layouts.app')


@section('title','メンテナンス設定')


@section('meta') @php
$active_key = 'maintenance';
$active_submenu = true;
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('style')
    @php
    /* 背景パス */
    $bg_image_path = \App\Http\Controllers\AdminBackGroundController::getBgSub();
    @endphp
    <style>
        /* ガチャの背景画像 */
        .bg_preview{
            background: no-repeat center center / cover;
            background-image: url({{ $bg_image_path }});
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
                {{-- <li class="breadcrumb-item"><a href="{{ route('admin.maintenance') }}"
                >{{ 'サイト背景' }}</a></li> --}}
                <li class="breadcrumb-item active" aria-current="page"
                >{{ 'メンテナンス設定' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ 'メンテナンス設定' }}</h2>

        {{-- <a href="{{url()->previous()}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a> --}}


        <section>
            <form action="{{ route('admin.maintenance.update') }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                <div class="row gy-5">
                    <div class="col-12 col-md-6">
                       プレビュー
                       <div class="card py-5 bg_preview bg-body" style="min-height:80vh;">
                            @include('maintenance.body')
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        @include('admin.maintenance._inputs')
                    </div>
                </div>


            </form>
        </section>

    </div>
@endsection
