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


        <div class="d-flex gap-3 mb-3">

            <a href="{{route('admin.gacha_title.create')}}"
            class="btn btn-primary text-white rounded-pill shadow "
            ><i class="bi bi-plus-lg me-2"></i>新規登録</a>

            @if( config('gacha.admin.settings') )
                <a href="{{route('admin.gacha.settings.edit_list')}}" class="btn btn-light rounded-pill border">
                    <div class="d-flex align-items-center gap-2"><i class="bi bi-gear me-2"></i>設定</div>
                </a>
            @endif

        </div>


        <section>


            <div class="row g-3">
                @foreach ($gacha_titles as $gacha_title)
                    <div class="col-6 col-md-3 col-lg-3">


                        <a href="{{route('admin.gacha_title.show',$gacha_title)}}" class="d-block">

                            <!--公開バッジ-->
                            <div class="px-2 mb-1">
                                @include('manuf_admin.gacha_title.common.published_statuse_badge')
                            </div>

                            <ratio-image-component
                            url="{{$gacha_title->image_samune_path}}"
                            style_class="{{$gacha_title->ratio.' ratio bg-body'}}"
                            bg_size="contain"
                            ></ratio-image-component>

                            <!--販売期間-->
                            <div class="my-1">
                                <div class="px-2" style="line-height:.8rem;">
                                    <span class="form-text fw-bold">販売期間:</span>

                                    <div class="form-text">
                                        {{ $gacha_title['sales_start_at']
                                        ?  $gacha_title['sales_start_at']->format('Y/m/d H:i')
                                        : '----/--/-- --:--' }}
                                        <span>~</span>
                                        {{ $gacha_title['sales_end_at']
                                        ? $gacha_title['sales_end_at']->format('Y/m/d H:i')
                                        : '----/--/-- --:--' }}
                                    </div>
                                </div>
                            </div>

                            <!--発送時期-->
                            <div class="{{ $gacha_title->estimated_shipping_label_style }} w-100"
                            >{{ $gacha_title->estimated_shipping_label }}</div>

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
