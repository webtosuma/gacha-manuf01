@extends('layouts.app')

<!--title-->
@section('title','タイトル')

<!--meta-->
@section('meta')
@endsection

<!--style-->
@section('style')
<style>
    /* サイトデフォルト背景 */
    body{
        background-image: url({{asset('storage/site/image/bg03.png')}});
    }
</style>
<style>
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


@section('content')
    <!--カルーセル-->
    <section class="bg-dark overflow-hidden" style="
    background: url({{asset('storage/site/image/bg02.jpg')}}) no-repeat center center/cover;
    ">
        <div class="container" style="">
            <div class="col-md-8 mx-auto py-">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators mb-0">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>


                    <div class="carousel-inner anm_opasity_e01" style="max-height:90vh;">
                        <a href="{{route('gacha','ex_gacha')}}" class="carousel-item pb- bg-dark active ">
                            <ratio-image-component
                            style_class="ratio ratio-4x3"
                            url="https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2F2_min_4ae1a498be.jpg&w=1200&q=75"
                            ></ratio-image-component>
                        </a>
                        <a href="{{route('gacha','ex_gacha')}}" class="carousel-item pb- bg-dark">
                            <ratio-image-component
                            style_class="ratio ratio-4x3"
                            url="https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2FLomance_cf4889ed08.jpg&w=1200&q=75"
                            ></ratio-image-component>
                        </a>
                        <a href="{{route('gacha','ex_gacha')}}" class="carousel-item pb- bg-dark">
                            <ratio-image-component
                            style_class="ratio ratio-4x3"
                            url="https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2Fonly_7eacc89593.jpg&w=1200&q=75"
                            ></ratio-image-component>
                        </a>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!--お知らせ-->
    {{-- <section class="bg-dark">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('お知らせ') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <a href="{{route('payment')}}">{{ __('ポイント購入') }}</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!--カテゴリー-->
    <section class="p-3 bg-dark">
        <div class="container overflow-auto">
            <nav class="nav gap-3 flex-nowrap" style="min-width:900px;">
                <a class="col-md fs-5 fw-bold btn btn-light rounded-pill disabled" aria-current="page" href="#"
                >ワンピース</a>
                <a class="col-md fs-5 fw-bold btn btn-light rounded-pill border-dark border-2" href="#">ポケモン</a>
                <a class="col-md fs-5 fw-bold btn btn-light rounded-pill border-dark border-2" href="#">遊戯王</a>
                <a class="col-md fs-5 fw-bold btn btn-light rounded-pill border-dark border-2" href="#">ドラゴンボール</a>
                {{-- <a class="col fs-5 fw-bold btn btn-light rounded-pill border-dark border-2 disabled" href="#" tabindex="-1" aria-disabled="true">準備中</a> --}}
            </nav>
        </div>
    </section>
    <!--ガチャ-->
    <section class="p-3 pb-5">
        <div class="container">

            <!--card-->
            <div class="row gy-5 my-3 overflow-hidden">
                @php
                $gachas = [
                    ['url'=>'https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2F2_min_4ae1a498be.jpg&w=1200&q=75'],
                    ['url'=>'https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2FLomance_cf4889ed08.jpg&w=1200&q=75'],
                    ['url'=>'https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2Fonly_7eacc89593.jpg&w=1200&q=75'],
                    ['url'=>'https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2FACE_b1742db956.jpg&w=1200&q=75'],
                    ['url'=>'https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2F1_754b55d72a.jpg&w=1200&q=75'],
                    ['url'=>'https://japan-toreca.com/_next/image?url=https%3A%2F%2Fjapan-toreca-strapi.imgix.net%2F3_cfa94dab25.jpg&w=1200&q=75'],
                ];
                @endphp
                @foreach ($gachas as $gacha)
                    <div class="col-12 col-md-6 col-lg-4 ">

                        <a href="{{route('gacha','ex_gacha')}}" class="card border-secondary border-3 shadow bg-white
                        text-dark text-center overflow-hidden text-decoration-none
                        hover_anime" style="border-radius:1rem;">

                            <!--image-->
                            <ratio-image-component
                            url="{{$gacha['url']}}" style_class="ratio ratio-4x3"
                            ></ratio-image-component>

                            <!--metter-->
                            <div class="card-body">
                                <h6 class="d-flex justify-content-between align-items-end">
                                    <p class="card-text m-0">
                                        残り 4,000/10,000
                                    </p>

                                    <div class="d-flex align-items-center gap-2">
                                        @include('includes.point_icon')

                                        <div class="">
                                            1回×<span class="fs-3">500</span>pt
                                        </div>
                                    </div>
                                </h6>

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"
                                    style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                        </a>


                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <form action="{{ route('gacha.play', 'ex_gacha') }}" method="post">
                                    @csrf
                                    <button type="submit" name="play_count" value="{{ 1 }}"
                                    class="btn btn-light fw-bold w-100 rounded-pill border-secondary border-2"
                                    >1回ガチャる</button>
                                </form>
                            </div>
                            <div class="col-6">
                                <form action="{{ route('gacha.play', 'ex_gacha') }}" method="post">
                                    @csrf
                                    <button type="submit" name="play_count" value="{{ 10 }}"
                                    class="btn btn-dark text- fw-bold w-100 rounded-pill border-secondary border-2"
                                    >10連ガチャる</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </section>

@endsection
