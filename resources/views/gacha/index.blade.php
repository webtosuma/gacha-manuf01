@extends('layouts.app')

<!--title-->
@section('title',$category_name.'のガチャ一覧')

<!--meta-->
@section('meta')
    @php
    $meta_title = $category_name.'のガチャ一覧';
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
        .carousel-indicators [data-bs-target] {
            border-radius: 100%;
            widows: 30px; height: 30px;
            margin-bottom:0;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            display: inline-block;
            width:  4rem;
            height: 4rem;
            background-repeat: no-repeat;
            background-position: 50%;
            background-size: 100% 100%;
        }

        .carousel-control-prev, .carousel-control-next {
            width: 3rem;
            opacity: .8;
        }
        .carousel-control-prev-icon {
            background-image:url({{asset('storage/site/image/carousel/prev.png')}});
        }
        .carousel-control-next-icon {
            background-image:url({{asset('storage/site/image/carousel/next.png')}});
        }
        .carousel-control-prev, .carousel-control-next{
            opacity: 1;
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


    <!-- 紹介キャンペーン　トースト -->
    {{-- @if( $category_code=='all' )
    <div class="toast-container position-fixed bottom-0 end-0 p-3 d-flex justify-content-end" style="z-index:99;">
        <div class="col-8 col-md-12">
            <div class="toast fade show border-0 rounded-3 overflow-hidden shadow" role="alert" aria-live="assertive" aria-atomic="true">

                <div class="toast-header justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body p-0">

                    <a href="{{route('settings.canpaing_introductory')}}" class="d-block">
                        <ratio-image-component
                        style_class="ratio ratio-4x3"
                        url="{{ asset( 'storage/'.'site/image/campaign_introductory/index.jpg' ) }}"
                        ></ratio-image-component>
                    </a>


                </div>
            </div>
        </div>
    </div>
    @endif --}}



    <!--カルーセル-->
    <section class="overflow-hidden" style="background:rgb(0, 0, 0,.8);">

        <div class="container p-3 bg-dar">
            <div class="mx-auto"  style="max-width: 900px;">
                <div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">

                    <!--image  お知らせスライド -->
                    <div class="carousel-inner">
                        @foreach ($slide_infos as $si => $slide_info)

                            <a href="{{ route('infomation.show',$slide_info) }}" class="carousel-item pb- bg-dark
                            {{ $si==0 ? 'active' : ''}}">

                                <div class="">
                                    <ratio-image-component
                                    style_class="ratio ratio-4x3"
                                    url="{{ $slide_info->image_path }}"
                                    ></ratio-image-component>
                                </div>

                            </a>
                        @endforeach

                        <!--image ガチャスライド -->
                        @foreach ($gachas as $gi => $gacha)

                            @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
                            <a href="{{ route('gacha',$params) }}" class="carousel-item pb- bg-dark
                            {{ $slide_infos->count()==0 && $gi==0 ? 'active' : ''}}">

                                <div class="">
                                    <ratio-image-component
                                    style_class="ratio ratio-4x3"
                                    url="{{ $gacha->image_path }}"
                                    ></ratio-image-component>
                                </div>

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
                        <!--お知らせスライド -->
                        @foreach ($slide_infos as $si => $slide_info)
                            <button type="button" data-bs-target="#carouselIndicators"
                            class="{{ $si==0 ? 'active' : ''}}"
                            data-bs-slide-to="{{$si}}" aria-current="true" aria-label="{{'Slide '.($si+1)}}x"></button>
                        @endforeach
                        <!--ガチャスライド -->
                        @foreach ($gachas as $gi => $gacha)
                            <button type="button" data-bs-target="#carouselIndicators"
                            class="{{ $slide_infos->count()==0 && $gi==0 ? 'active' : ''}}"
                            data-bs-slide-to="{{$gi+$slide_infos->count()}}" aria-current="true" aria-label="{{'Slide '.($gi+$slide_infos->count()+1)}}x"></button>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </section>


    <!--カテゴリー-->
    @php
    $bg = $category_code=='all' ? 'bg-whiteee' : '';
    @endphp
    <section class="py-3 {{$bg}}">
        <div class="container px-0 col-md-12 mx-auto overflow-auto">
            <nav class="nav gap-1 flex-nowrap" style="min-width:{{$categories->count()*6 + 10}}rem;">
                @php
                $sc = "col fs- py-2 fw-bold btn btn-dark border-0";
                $style_class = $category_code=='all' ? $sc.' disabled bg-primary' : $sc;
                @endphp
                <a  href="{{ route('gacha_category','all') }}"
                class="{{ $style_class }}">{{ 'すべて' }}</a>


                @foreach ($categories as $category)
                    @php
                    $style_class = $category_code == $category->code_name ? $sc.' disabled bg-primary' : $sc;
                    @endphp

                    <a  href="{{ route('gacha_category', $category->code_name ) }}"
                    class="{{ $style_class }}">{{ $category->name }}</a>
                @endforeach
            </nav>
        </div>
    </section>


    <!--ガチャ-->
    <section class="py-3 pb-5">
        <div class="container" style="min-height:50vh;">

            <!--card-->
            <div class="row gy-5 overflow-hidden">
                @foreach ($gachas as $gacha)
                    <div class="col-12 col-md-6 col-lg-4 ">

                        @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
                        <a href="{{route('gacha',$params)}}"
                        class="card border-secondary border-3 shadow bg-white
                        text-dark text-center overflow-hidden text-decoration-none
                        hover_anime" style="border-radius:1rem;">


                            <!--image-->
                            @include('gacha.common.top_image')

                            <!--metter-->
                            <div class="card-body py-0">
                                <div class="d-flex align-items-center justify-content-center gap-2 fs-5">
                                    @include('includes.point_icon')

                                    <div class="">
                                        1回×
                                        <span class="fs-3">
                                            <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                                        </span>pt
                                    </div>
                                </div>
                                <div class="progress">
                                    @php
                                    $ratio = $gacha->remaining_ratio;
                                    $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
                                    $style_class = 'progress-bar progress-bar-striped '.$bg_color
                                    @endphp
                                    <div class="{{ $style_class }}" role="progressbar"
                                    style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"></div>
                                </div>
                                <p class="form-text text-center m-0">
                                    残り
                                    <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                                    /
                                    <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                                </p>
                            </div>
                        </a>

                        <!--play_buttons-->
                        @include('gacha.common.play_buttons')

                    </div>
                @endforeach
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
    <section class="bg-" style="background:rgb(0, 0, 0, 1);">
        <div class="container py-5">

            <h3 class="text-center text-white fs-3 fw-bold mb-4 py-3">公式 X（旧Twitter）</h3>


            <div class="col-md-8 mx-auto bg-dark rounded-4 overflow-auto" style="max-height:90vh;">
                <a class="twitter-timeline" href="https://twitter.com/CardFesta7627?ref_src=twsrc%5Etfw">Tweets by CardFesta7627</a>
            </div>
        </div>
    </section>




@endsection
