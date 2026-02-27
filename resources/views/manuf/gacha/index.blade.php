@extends('manuf.layouts.app')


<!--title-->
@section('title')
    @php
    $metas = \App\Models\Text::getMeta();//DB登録情報
    $title = $category_code=='all' || !isset($category_name) ? $metas['title'] : $category_name.'のガチャ一覧';
    @endphp

    {{$title}}
@endsection



<!--meta-->
@section('meta')
    @php
    $meta_title = $category_code=='all' ? null : $category_name.'のガチャ一覧';
    @endphp
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

        /* タブメニュー */
        /* .nav-link{
            color: rgb(33,37,41);
            font-size: 1.25rem
        }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            color: #fff;
            background-color:rgb(33,37,41) !important;
        } */

        /* ガチャのホバーアニメーション */
        .hover_anime:hover{
            position: relative;
            transform: scale(.97) translateY(0rem);
            opacity: .7;
            transition: all .2s;
        }
    </style>


    <!-- rainbow-css-->
    @include('gacha.common.rainbow-css')


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
    <script src="{{ asset('js/splide.js') }}"></script>

@endsection



@section('content')


    {{-- <section class="bg- " style="height:4.2rem;"
    data-aos="fade-in"
    ></section> --}}

    {{-- <section class="bg- " style="height:1rem;"
    data-aos="fade-in"
    ></section> --}}

    <section class="overflow-hidden bg-rainbow-index"
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
            @include('manuf.gacha.section.category.index')
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

        ></u-gacha-list>
        {{-- is_desc_popularity="{{$search_key=='desc_popularity'?1:0}}" --}}

    </section>



    <!--お知らせ-->
    @include('gacha.section.infomation')



@endsection
