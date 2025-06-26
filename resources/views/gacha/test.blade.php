@extends('layouts.app')

<!--title-->
@section('title')
    @php
    $title = config('app.name')."|オンラインガチャを24時間365日楽しめる！ ";
    $title = $category_code=='all' ? $title : $category_name.'のガチャ一覧';
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

        /* カルーセル */
        /* .carousel-indicators [data-bs-target] {
            border-radius: 100%;
            widows: 30px; height: 30px;
            margin-bottom:0;
        } */
        .carousel-control-prev-icon, .carousel-control-next-icon {
            display: inline-block;
            width:  2rem;
            height: 2rem;
            background-repeat: no-repeat;
            background-position: 50%;
            background-size: 100% 100%;
        }
        .carousel-indicators{
            z-index: 7;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 3rem;
            opacity: 1;
            z-index: 5;
        }

        .carousel-control-prev-icon {
            background-image:url({{asset('storage/site/image/carousel/prev.png')}});
        }
        .carousel-control-next-icon {
            background-image:url({{asset('storage/site/image/carousel/next.png')}});
        }

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
        /* .hover_anime:hover{
            position: relative;
            transform: rotate3d(0, 1, 0, 360deg);
            transition: all .2s;
        } */

        /* ガチャのホバーアニメーション */
        .hover_anime:hover{
            position: relative;
            animation: shake 1s infinite ease-in-out;
        }
        /* 揺れる動きのキーフレーム */
        @keyframes shake {
            0%, 100% {
                transform: rotate(0deg);
            }
            10% {
                transform: rotate(-8deg);
            }
            20% {
                transform: rotate(8deg);
            }
            30% {
                transform: rotate(-4deg);
            }
            40% {
                transform: rotate(4deg);
            }
            50% {
                transform: rotate(0deg);
            }
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
    {{-- <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    <script src="{{ asset('js/splide.js') }}"></script> --}}

@endsection



@section('content')


    <section class="bg-dark" style="height:5.2rem;"
    data-aos="fade-in"
    ></section>



    {{-- <section class="overflow-hidden bg-dark" style="background:rgb(0, 0, 0,.0);"
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
    <div class="bg-secondary-subtle">
        @include('gacha.section.category')
    </div>

    <!--ガチャ-->
    <section class="py-3 pb-5">



            <u-gacha-list
            token=        "{{ csrf_token() }}"
            category_code="{{ $category_code }}"
            search_key   ="{{ $search_key }}"
            r_api_gacha_list="{{ route('gacha.api.list') }}"
            sm_card="{{$card_size=='sm'?1:0}}"
            ></u-gacha-list>




            <!--card-->
            {{-- <div class="row overflow-hidden g-3 g-md-5 mx-0 pb-4 {{ $card_size=='sm'? 'gy-4':'gy-5'}}"
            data-aos="zoom-in"
            >


                <!-- countdown gacha card -->
                @if ($card_size == 'sm' )
                    @include('gacha.section.countdown_gacha_card_sm')
                @else
                    @include('gacha.section.countdown_gacha_card')
                @endif


                <!-- nomal gacha card -->
                @if ($card_size == 'sm' )
                    @include('gacha.section.nomal_gacha_card_sm')
                @else
                    @include('gacha.section.nomal_gacha_card')
                @endif


            </div> --}}


    </section>



    <!--お知らせ-->
    {{-- @include('gacha.section.infomation') --}}



@endsection
