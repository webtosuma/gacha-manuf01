@extends('store.layouts.app')

<!----- title ----->
@section('title','商品ストアー')


@section('style')
    <!-- splide css-->
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">
@endsection


@section('script')

    <!-- splide js -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    <script src="{{ asset('js/splide_store.js') }}"></script>

@endsection


@section('content')

    <!--スライド-->
    @if( count($slides)>0 )
        <section class="bg- pt-3" style="background:rgb(0, 0, 0,.0);">

            <!-- PC -->
            <div id="splide_store_pc" class="splide d-none d-md-block" aria-label="Splideの基本的なHTML">
                @include('store.section.common.splide')
            </div>
            <!-- Mobile -->
            <div id="splide_store_mobile" class="splide d-md-none" aria-label="Splideの基本的なHTML">
                @include('store.section.common.splide')
            </div>

        </section>
    @endif

    <!--カテゴリーから探す-->
    @if($categories->count()>1)
        <section>
            <div class="container py-4 px-">

                <h5 class="fs- fw-bold px-3">カテゴリーから探す</h5>

                <div class="row g-0">
                    @foreach ($categories as $category)

                        <div class="col-6 col-md-auto">
                            <a href="{{route('store.search',['category_code_name'=>$category->code_name])}}"
                            class="btn h-100 fw-bold
                            d-flex gap-3 flex-column align-items-center justify-content-center
                            ">
                                <div style="width:6rem;">
                                    <ratio-image-component
                                    url="{{ $category->top_store_item_image_path }}"
                                    style_class="ratio ratio-1x1 bg-body border rounded-pill"
                                    ></ratio-image-component>
                                </div>

                                {{ $category->name }}
                            </a>
                        </div>

                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!--各セクション-->
    @foreach ($section_group as $section)
        @php
        $line_id     = $section['line_id'];
        $line_label  = $section['line_label'];
        $line_r_more = $section['line_r_more'];
        $store_items = $section['store_items'];
        @endphp
        <!--PC-->
        <section class="d-none d-md-block">
            <div class="container py-4 px-0">
                <h5 class="fs- fw-bold px-3">{{$line_label}}</h5>
                <div id="{{$line_id}}"
                class="splide splide_store_item "
                aria-label="{{$line_label}}">

                    @include('store.section.common.spride_store_item')

                </div>
            </div>
        </section>
        <!--mobile-->
        <section class="d-md-none">
            <div class="container py-4">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fs- fw-bold">{{$line_label}}</h5>

                    <a href="{{$line_r_more}}"
                    class="btn btn-sm text-primary"
                    >もっと見る</a>
                </div>

                <div class="row g-3">
                    @include('store.section.common.card')
                </div>
            </div>
        </section>

    @endforeach


    <!--ガチャ-->
    @if($gachas->count()>0)
        <section>
            <div class="container py-4 rounded-4">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fs- fw-bold">{{'ガチャ'}}</h5>

                    <a href="{{route('gacha_category')}}"
                    class="btn btn-sm text-primary"
                    >もっと見る</a>
                </div>


                @include('store.section.common.gachas')

            </div>
        </section>
    @endif


    <!--お知らせ-->
    @if( $infomations->count()>0 )

        @include('store.section.infomation')

    @endif





@endsection
