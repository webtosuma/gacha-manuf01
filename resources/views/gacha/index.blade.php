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

    <section class="bg-body" style="height:4.2rem;"></section>


    @if ( env('NEW_TICKET_SISTEM',false) )
        <!--TOP 3連メニュー-->
        <section  class="bg-body border-top" >
            <div class="container">
                <div class="row">
                    <div class="col p-0">
                        <a href="{{ route('ticket_store') }}" class="btn py-1 rounded-0 w-100 border-start" >
                            <img src="{{asset('storage/site/image/ticket/dark.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:20px; height:20px;">

                            <div class="text-secondary mt-1" style="font-size:11px; line-height:11px;">商品と交換</div>
                        </a>
                    </div>
                    <div class="col p-0">
                        <a href="{{ route('infomation') }}" class="btn py-1 rounded-0 w-100 border-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">
                                <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"/>
                            </svg>
                            <div class="text-secondary mt-1" style="font-size:11px; line-height:11px;">お知らせ</div>
                        </a>
                    </div>
                    <div class="col p-0">
                        <a href="{{ route('timeline') }}" class="btn py-1 rounded-0 w-100 border-start border-end">
                            <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                            alt="タイムライン" class="d-block mx-auto" style=" width:20px; height:20px;">
                            <div class="text-secondary mt-1" style="font-size:11px; line-height:11px;">タイムライン</div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif


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
