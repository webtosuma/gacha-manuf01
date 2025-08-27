@extends('layouts.app')

<!--title-->
@section('title')
    @php
    $title = config('app.name')."|オンラインガチャを24時間365日楽しめる！ ";
    $title = $category_code=='all' || !isset($category_name) ? $title : $category_name.'のガチャ一覧';
    @endphp

    {{$title}}
@endsection


<!--meta-->
@section('meta')
    @php
    $meta_title = $category_code=='all' ? null : $category_name.'のガチャ一覧';
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
    <style>
        main{ padding-top: 0rem; }


        /* タブメニュー */
        .nav-link{
            color: rgb(33,37,41);
            font-size: 1.25rem
        }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            color: #fff;
            background-color:rgb(33,37,41) !important;
        }

        /* ガチャのホバーアニメーション */
        .hover_anime:hover{
            position: relative;
            transform: scale(.97) translateY(0rem);
            opacity: .7;
            transition: all .2s;
        }
    </style>

    <!-- splide css-->
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">

@endsection


@section('script')
    <!--X timeline-->
    {{-- <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> --}}

    <!-- splide js -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    <script src="{{ asset('js/splide.js') }}"></script>

@endsection



@section('content')


    <section class="bg- " style="height:4.2rem;"
    data-aos="fade-in"
    ></section>



    <section class="overflow-hidden bg-" style="background:rgb(0, 0, 0,.0);"
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

    </section>



    <!--カテゴリー-->
    @if($categories->count()>1)
        <div class="bg-">
            @include('gacha.section.category.index')
        </div>
    @endif


    <!--ガチャ-->
    <section class="py-3 pb-5" style="min-height:80vh;">

        <u-gacha-list
        token=        "{{ csrf_token() }}"
        category_code="{{ $category_code }}"
        search_key   ="{{ $search_key }}"
        r_api_gacha_list="{{ route('gacha.api.list') }}"
        sm_card="{{$card_size=='sm'?1:0}}"

        card_size ="{{$card_size}}"
        search_key="{{$search_key}}"

        ></u-gacha-list>
        {{-- is_desc_popularity="{{$search_key=='desc_popularity'?1:0}}" --}}

    </section>



    <!--お知らせ-->
    @include('gacha.section.infomation')



@endsection
