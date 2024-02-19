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

    <section class="bg-dark" style="height:4.2rem;"></section>

    <!--カルーセル-->
    <section class="overflow-hidden" style="background:rgb(0, 0, 0,.8);">

        <div class="container p-3 bg-dar">
            <div class="mx-auto"  style="max-width: 900px;">
                <div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">

                    <!--image スライド -->
                    <div class="carousel-inner">
                        @foreach ($slides as $si => $slide)

                            <a href="{{ $slide['href'] }}" class="carousel-item pb- bg-dark
                            {{ $si==0 ? 'active' : ''}}">

                                <!--image-->
                                @if( $slide['type'] == 'gacha' )

                                    @php $gacha = $slide['gacha'];@endphp
                                    @include('gacha.common.top_image')

                                @else
                                    <div class="ratio ratio-4x3 position-relative">
                                        <div style="z-index:1;">
                                            <ratio-image-component
                                            style_class="ratio ratio-4x3"
                                            url="{{ $slide['image'] }}"
                                            ></ratio-image-component>
                                        </div>


                                        <div class="absolute h-100 w-100 bg-dark d-flex align-items-center justify-content-center"
                                        style="z-index:0;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </a>
                        @endforeach
                    </div>


                    <!--side menu-->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                    </button>


                    <!--bottom menu-->
                    <div class="carousel-indicators mb-0">
                        @foreach ($slides as $si => $slide)
                            <button type="button" data-bs-target="#carouselIndicators"
                            class="{{ $si==0 ? 'active' : ''}}"
                            data-bs-slide-to="{{$si}}" aria-current="true" aria-label="{{'Slide '.($si+1)}}x"></button>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </section>


    <!--カテゴリー-->
    <section class="mt-3 mb-2">
        <div class="container px-0 col-md-12 mx-auto overflow-auto">
            <nav class="nav gap-1 flex-nowrap" style="min-width:{{$categories->count()*6 + 10}}rem;">
                @php
                $sc = "col fs- py-2 fw-bold btn btn-dark border-0";
                $style_class = $category_code=='all' ? $sc.' disabled bg-primary' : $sc;
                $params = ['category_code'=>'all', 'search_key'=>$search_key];

                @endphp
                <a  href="{{ route('gacha_category',$params) }}"
                class="{{ $style_class }}">{{ 'すべて' }}</a>


                @foreach ($categories as $category)
                    @php
                    $style_class = $category_code == $category->code_name ? $sc.' disabled bg-primary' : $sc;
                    $params = ['category_code'=>$category->code_name, 'search_key'=>$search_key];
                    @endphp

                    <a  href="{{ route('gacha_category', $params ) }}"
                    class="{{ $style_class }}">{{ $category->name }}</a>
                @endforeach
            </nav>
        </div>
    </section>


    <!--絞り込みキー-->
    <section class="mb-3">
        <div class="container px-0 col-md-12 mx-auto overflow-auto">
            @php
            $sc = "col- fs- py-2 fw-bold btn btn-sm btn-light border px-3 rounded-pill";
            $search_key = $search_key ? $search_key : 'desc_crated';
            @endphp
            <nav class="nav gap-1 flex-nowrap" style="min-width:{{count($searchs)*5 + 10}}rem;">

                @foreach ($searchs as $search)
                    @php
                    $style_class = $search_key==$search['key'] ? $sc.' disabled bg-primary text-white border-primary' : $sc;

                    $params = ['category_code'=>$category_code, 'search_key'=>$search['key']];
                    @endphp


                    <a id="{{$search['key']}}"  href="{{route('gacha_category',$params)}}"
                    class="{{ $style_class }}">{{ $search['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </section>


    <!--ガチャ-->
    <section class="py-3 pb-5">
        <div class="container" style="min-height:50vh;">




            <!--card-->
            <div class="row gy-5 overflow-hidden">



                <!-- countdown gachas -->
                @foreach ($countdown_gachas as $countdown_gacha)
                    <div class="col-12 col-md-6 col-lg-4  ">
                        <div class="card border-secondary border-3 shadow bg-white
                        text-dark text-center overflow-hidden text-decoration-none
                        hover_anime position-relative" style="border-radius:1rem;">

                            <u-countdown-gacha initial_time="{{$countdown_gacha->initial_time}}" ></u-countdown-gacha>

                            <div class="position-relative">
                                <!--loading-->
                                <div class="ratio ratio-4x3">
                                    <div class="bg-dark d-flex align-items-center justify-content-center"
                                    style="z-index:0;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>

                                <!--gacha image-->
                                <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden"
                                style="z-index:0; -ms-filter: blur(10px); filter: blur(10px); ">
                                    <ratio-image-component
                                    url="{{ $countdown_gacha->image_path }}" style_class="ratio ratio-4x3"
                                    ></ratio-image-component>
                                </div>
                            </div>


                            <div class="card-body bg-dark" style="height:4.3rem;"></div>
                        </div>
                    </div>
                @endforeach


                <!--nomal gacha card-->
                @forelse ($gachas as $gacha)
                    <div class="col-12 col-md-6 col-lg-4  ">


                        <a href="{{$gacha->route}}"
                        class="card border-secondary border-3 shadow bg-white
                        text-dark text-center overflow-hidden text-decoration-none
                        hover_anime" style="border-radius:1rem;">


                            <!--image-->
                            @include('gacha.common.top_image')

                            <!--metter-->
                            @include('gacha.common.metter')

                        </a>



                        <!--play_buttons-->
                        @include('gacha.common.play_buttons')

                    </div>
                @empty
                    <div class="col-12 text-secondary bg-light-subtle
                    p-3 fs-5 rounded-3 shadow
                     ">
                        *該当するガチャがありません。
                    </div>
                @endforelse
            </div>


        </div>
    </section>



    <!--お知らせ-->
    <section class="bg- mb-5">
        <div class="container py-5">
            <div class="col-md-8 mx-auto">

                {{-- <h3 class="text-center text-white fw-bold mb-4 fs-1">お知らせ</h3> --}}

                <div class="list-group list-group-flush shadow-sm rounded-4"
                style="background:rgb(0, 0, 0, .8;">
                    <div class="list-group-item border-0">
                        <h3 class="text-center text-white my-3 fw-bold mb-4 fs-2 border-bottom border-primary border-2 pb-3"
                        >お知らせ</h3>
                    </div>

                    @forelse ($infomations as $infomation)
                        <div class="list-group-item list-group-item-action border-0 pozition-relative">
                            <a href="{{ route('infomation.show',$infomation) }}" class="text-dark">
                                <div class="d-flex align-items-center px-3">
                                    <div class="col">
                                        <div class="row py-2">

                                            <div class="col-auto text-primary">
                                                {{ $infomation->created_at->format('Y.m.d') }}
                                            </div>
                                            <div class="col-12 col-md text-white">
                                                {{ $infomation->title }}
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto text-primary">
                                        <i class="bi bi-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="list-group-item border-0 pozition-relative">
                            <div class="">
                                * お知らせはありません
                            </div>
                        </div>
                    @endforelse

                    <div class="list-group-item border-0 text-end">
                        <a href="{{route('infomation')}}" class="btn btn- text-white ">もっと見る ></a>
                    </div>
                </div>

                {{-- <div class="text-end mt-3">
                    <a href="{{route('infomation')}}" class="btn btn- text-white ">もっと見る ></a>
                </div> --}}
            </div>
        </div>
    </section>


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
