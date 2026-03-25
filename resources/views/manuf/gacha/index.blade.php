@extends('manuf.layouts.app')


<!--title-->
@section('title')
    @php
    $metas = \App\Models\Text::getMeta();//DB登録情報
    // $title = $category_code=='all' || !isset($category_name) ? $metas['title'] : $category_name.'のガチャ一覧';
    $title = $metas['title'];
    @endphp

    {{$title}}
@endsection



<!--meta-->
@section('meta')
    @php
    // $meta_title = $category_code=='all' ? null : $category_name.'のガチャ一覧';
    @endphp
@endsection



<!--style-->
@section('style')
    <style>
        /* サイトデフォルト背景 */
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
    </style>

    @include('manuf.gacha.common.css')


    <!-- rainbow-css-->
    @include('gacha.common.rainbow-css')


    <!-- splide css-->
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">

    <style>
        /* ガチャのホバーアニメーション */
        .hover_anime:hover{
            position: relative;
            transform: scale(.97) translateY(0rem);
            opacity: .7;
            transition: all .2s;
        }
    </style>


@endsection



@section('script')

    <!-- splide js -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    <script src="{{ asset('js/splide.js') }}"></script>

@endsection



@section('content')

    {{-- <section class="overflow-hidden bg-rainbow-index mt-3"
    data-aos="fade-in"
    >

        <!-- PC -->
        <div id="splide_pc" class="splide d-none d-md-block" aria-label="Splideの基本的なHTML">
            @include('gacha.section.common.splide')
        </div>
        <!-- Mobile -->
        <div id="splide_mobile" class="splide d-md-none" aria-label="Splideの基本的なHTML">
            @include('gacha.section.common.splide')
        </div>

    </section> --}}



    <!--カテゴリー-->
    @if($categories->count()>1)
        <div class="bg-">
            @include('manuf.gacha.section.category.index')
        </div>
    @endif


    <!--ガチャ-->
    <section class="container py-3 pb-5" style="min-height:80vh;"
    data-aos="fade-in"
    >

        <div class="row g-2 mb-4 justify-content- ">
            <div class="col-12">
                <h5 class="fw-bold">発送予定から選択</h5>
            </div>

            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-info rounded-pill
                text-white fs-6
                " style="width:6rem; height:6rem;">
                    <span>すべて</span>
                </button>
            </div>
            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-warning rounded-pill
                text- fs-6
                " style="width:6rem; height:6rem;">
                    <span>XX月<br>発送予定</span>
                </button>
            </div>
            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-warning rounded-pill
                text- fs-6
                " style="width:6rem; height:6rem;">
                    <span>XX月<br>発送予定</span>
                </button>
            </div>
            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-success rounded-pill
                text-white fs-6
                " style="width:6rem; height:6rem;">
                    <span>すぐ発送</span>
                </button>
            </div>

        </div>




        <div class="row g-3 gy-4">
            @foreach ($gacha_titles as $gacha_title)
                <div class="col-4 col-sm-3 col-lg-auto">
                    <a href="{{$gacha_title->r_show}}" class="btn p-0 w-100  hover_anime "
                    style="max-width:200px;"
                    >

                        @php $machine_text = true; @endphp
                        @include('manuf.gacha.common.machine_icon')


                    </a>
                </div>
            @endforeach
        </div>


        {{-- <u-manuf-gacha-list
        token=        "{{ csrf_token() }}"
        category_code="{{ $category_code }}"
        search_key   ="{{ $search_key }}"
        r_api_gacha_list="{{ route('gacha.api.list') }}"
        sm_card="{{$card_size=='sm'?1:0}}"
        card_size ="{{$card_size}}"
        ></u-manuf-gacha-list> --}}

    </section>



    <!--お知らせ-->
    @include('gacha.section.infomation')



@endsection
