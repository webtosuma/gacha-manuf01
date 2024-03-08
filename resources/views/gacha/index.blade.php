@extends('layouts.app')

<!--title-->
@section('title')
    @php
    $title = "cardFesta（カードフェスタ）|オンラインオリパ・ネットオリパを24時間365日楽しめる！国内送料は無料！ ";
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
        body{
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
            width:  4rem;
            height: 4rem;
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
        .hover_anime:hover{
            position: relative;
            transform: scale(1.05) translateY(-1rem);

            transition: all .2s;
        }



    </style>
@endsection


@section('script')
    <!--X timeline-->
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
@endsection



@section('content')

    <section class="bg-white" style="height:4.2rem;"></section>


    <!--カルーセル-->
    @include('gacha.section.carousel')


    <!--カテゴリー-->
    @include('gacha.section.category')


    <!--ガチャ-->
    <section class="py-3 pb-5">
        <div class="container" style="min-height:50vh;">


            <!--card-->
            <div class="row overflow-hidden g-2 {{ $card_size=='sm'? 'gy-4':'gy-5'}}">


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


            </div>


        </div>
    </section>



    <!--お知らせ-->
    @include('gacha.section.infomation')



    <!--twitterタイムライン-->
    <section id="timeline" class="bg-" style="background:rgb(0, 0, 0, 1);">
        <div class="container py-5">

            <h3 class="text-center text-white fs-3 fw-bol mb-4 py-3">

                <span>公式</span>
                <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
                alt="xロゴ" class="d-inline-block mb-2" style="height:1.8rem;">
                <span class="ms-3">（旧Twitter）</span>

            </h3>


            <div class="col-md-8 mx-auto bg- text-center rounded-4 overflow-auto" style="max-height:90vh;">
                <a class="twitter-timeline" href="https://twitter.com/CardFesta7627?ref_src=twsrc%5Etfw">...読み込み中</a>
            </div>
        </div>
    </section>


@endsection
