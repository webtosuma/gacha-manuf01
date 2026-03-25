@extends('manuf_admin.layouts.app')


@section('title','ガチャタイトル')


@section('meta') @php
$active_key = 'gacha_title';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ガチャタイトル</li>
            </ol>
        </nav>


        @if( config('gacha.admin.settings') )
            <div class="d-flex gap-3 mb-3">
                <a href="{{route('admin.gacha.settings.edit_list')}}" class="btn btn-light rounded-pill shadow py-1">
                    <div class="d-flex align-items-center gap-2"><i class="bi bi-gear fs-3"></i>設定</div>
                </a>
            </div>
        @endif


        <section>

            <div class="mb-3">
                <a href="{{route('admin.gacha_title.create')}}"
                class="btn btn-primary text-white rounded-pill"
                ><i class="bi bi-plus-lg me-2"></i>新規登録</a>
            </div>

            <div class="row g-3">
                @foreach ($gacha_titles as $gacha_title)
                    <div class="col-6 col-md-3 col-lg-2">


                        <a href="{{route('admin.gacha_title.show',$gacha_title)}}" class="d-block">

                            <ratio-image-component
                            url="{{$gacha_title->image_samune_path}}"
                            style_class="{{$gacha_title->ratio.' ratio bg-body'}}"
                            bg_size="contain"
                            ></ratio-image-component>

                            <!--公開ステータス-->
                            <div class="px-2">
                                @include('manuf_admin.gacha_title.common.published_statuse')
                            </div>

                        </a>


                    </div>
                @endforeach
            </div>
        </section>


        {{-- <section class="mb-5">
            <a-gacha-list
            token        ="{{csrf_token()}}"
            r_api_list   ="{{route('admin.api.gacha')}}"
            r_create     ="{{ route('admin.gacha.create') }}"
            category_code="{{$category_code}}"
            ></a-gacha-list>
        </section> --}}


    </div>
@endsection
