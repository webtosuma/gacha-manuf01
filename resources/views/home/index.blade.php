@extends('layouts.app')

<!--title-->
@section('title','タイトル')

<!--meta-->
@section('meta')
@endsection


<!--script-->
@section('script')
@endsection


<!--style-->
@section('style')
<style>
    /* カルーセル */
    .carousel-indicators [data-bs-target] {
        border-radius: 100%;
        widows: 30px; height: 30px;
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        display: inline-block;
        width:  4rem;
        height: 4rem;
        background-repeat: no-repeat;
        background-position: 50%;
        background-size: 100% 100%;
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
    <!--カテゴリー-->
    <section class="p-3">
        <div class="container overflow-auto">
            <nav class="nav gap-3 " style="min-width: 900px;">
                <a class="col btn btn-dark rounded-pill disabled" aria-current="page" href="#"
                >ポケモン</a>
                <a class="col btn btn-light rounded-pill border-dark border-2" href="#">遊戯王</a>
                <a class="col btn btn-light rounded-pill border-dark border-2" href="#">ワンピース</a>
                <a class="col btn btn-light rounded-pill border-dark border-2 disabled" href="#" tabindex="-1" aria-disabled="true">準備中</a>
            </nav>
        </div>
    </section>
    <!--カルーセル-->
    <section class="bg-dark overflow-hidden" style="
    background: url({{asset('storage/site/image/bg02.jpg')}}) no-repeat center center/cover;
    ">
        <div class="container" style="">
            <div class="col-md-8 mx-auto py-4">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>


                    <div class="carousel-inner anm_opasity_e01" style="max-height:80vh;">
                        <a href="#" class="carousel-item active">
                            <ratio-image-component
                            style_class="ratio ratio-4x3"
                            url="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTXP8tAN9BxO4_PIURtB5pcUmsPyiJUemNYkuMbT-fu2DYqQH6ZDrI76jzPljmmnzTQjEw&usqp=CAU"
                            ></ratio-image-component>
                        </a>
                        <a href="#" class="over carousel-item">
                            <ratio-image-component
                            style_class="ratio ratio-4x3"
                            url="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvous_pZniWGJmmwzwZNCNzGRheeOGbc1oow&usqp=CAU"
                            ></ratio-image-component>
                        </a>
                        <a href="#" class="over carousel-item">
                            <ratio-image-component
                            style_class="ratio ratio-4x3"
                            url="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSj3IN1KzZS2wh4a0uP_N3c5fvzUJo3fEGb8w&usqp=CAU"
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

    <!--ガチャ-->
    <section class="p-3 pb-5 bg-body" style=" background: no-repeat center center / cover fixed;
    background-image: url({{asset('storage/site/image/bg03.png')}});
    ">
        <div class="container">

            <!--card-->
            <div class="row gy-5 my-3 overflow-hidden">
                @php
                $gachas = [
                    ['url'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTXP8tAN9BxO4_PIURtB5pcUmsPyiJUemNYkuMbT-fu2DYqQH6ZDrI76jzPljmmnzTQjEw&usqp=CAU'],
                    ['url'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvous_pZniWGJmmwzwZNCNzGRheeOGbc1oow&usqp=CAU'],
                    ['url'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSj3IN1KzZS2wh4a0uP_N3c5fvzUJo3fEGb8w&usqp=CAU'],
                    ['url'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTXP8tAN9BxO4_PIURtB5pcUmsPyiJUemNYkuMbT-fu2DYqQH6ZDrI76jzPljmmnzTQjEw&usqp=CAU'],
                    ['url'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvous_pZniWGJmmwzwZNCNzGRheeOGbc1oow&usqp=CAU'],
                    ['url'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSj3IN1KzZS2wh4a0uP_N3c5fvzUJo3fEGb8w&usqp=CAU'],
                ];
                @endphp
                @foreach ($gachas as $gacha)
                    <div class="col-12 col-md-4 ">

                        <a href="#" class="card border-secondary border-3 shadow bg-white
                        text-dark text-center overflow-hidden text-decoration-nonte
                        hover_anime" style="border-radius:1rem;">

                            <ratio-image-component
                            url="{{$gacha['url']}}" style_class="ratio ratio-4x3"
                            ></ratio-image-component>

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
                                {{-- <div class="row g-2">
                                    <div class="co-12l">
                                        <a href="#" class="btn btn-danger fw-bold w-100 rounded-pill">
                                            <div class="">10連ガチャる
                                            <span class="badge rounded-pill bg-warning text-dark">5,000pt</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="co-12l">
                                        <a href="#" class="btn btn-light fw-bold w-100 rounded-pill">
                                            <div class="">1回ガチャる
                                            <span class="badge rounded-pill bg-warning text-dark">500pt</span>
                                            </div>
                                        </a>
                                    </div>
                                </div> --}}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>


        </div>
    </section>

@endsection
