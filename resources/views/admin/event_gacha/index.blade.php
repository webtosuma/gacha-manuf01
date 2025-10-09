@extends('admin.layouts.event')

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
            background: no-repeat center center / cover;
            background-image: url({{ $bg_image }});
        }
    </style>
    <style>
        main{ padding-top: 0rem; }


        /* ガチャのホバーアニメーション */
        .hover_anime:hover{
            position: relative;
            transform: scale(.97) translateY(0rem);
            opacity: .7;
            transition: all .2s;
        }
    </style>

    <!-- splide css-->
    {{-- <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet"> --}}

@endsection


@section('script')

    <!-- splide js -->
    {{-- <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    <script src="{{ asset('js/splide.js') }}"></script> --}}

@endsection



@section('content')


    {{-- <section class="bg- " style="height:4.2rem;"
    data-aos="fade-in"
    ></section> --}}



    <!--カテゴリー-->
    @if($categories->count()>1)
        <div class="bg-">
            @include('admin.event_gacha.category.index')
        </div>
    @endif


    <!--ガチャ-->
    <section class="py-3 pb-5" style="min-height:80vh;">

        <e-gacha-list
        token=        "{{ csrf_token() }}"
        category_code="{{ $category_code }}"
        search_key   ="{{ $search_key }}"
        r_api_gacha_list="{{ route('gacha.api.list') }}"
        sm_card="{{$card_size=='sm'?1:0}}"
        card_size ="{{$card_size}}"
        search_key="{{$search_key}}"
        ></e-gacha-list>

    </section>




@endsection
