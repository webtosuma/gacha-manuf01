@extends('layouts.app')

<!----- title ----->
@section('title','ご友人紹介キャンペーン・ご紹介用URL')


@section('meta')
    @php
        $meta_title = 'ご友人紹介キャンペーン・ご紹介用URL';
        $meta_image = asset( 'storage/'.'site/image/campaign_introductory/index.png' );
    @endphp
@endsection

@section('style')
    <style>
        main{ padding-top: 0rem; }
        /* カスタム背景 */
        #bgWindow{
            background-image: url({{ asset( 'storage/'.'site/image/campaign_introductory/bg04.jpg' ) }});
        }
    </style>
@endsection


@section('content')

    <section class="bg-dark" style="height:4.2rem;"></section>

    <!--breadcrumb-->
    <div class="container mt-md-3  bg-light">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">ご友人紹介キャンペーン</li>
            </ol>
        </nav>
    </div>


    <div class="container py-md-4 mb-5">
        {{-- <h3>ご友人紹介キャンペーン</h3> --}}
        <div class="mx-auto" style="max-width:900px;">

            <div class="card border-0 rounded-4  bg-white overflow-hidden">


                <img src="{{ $image_path }}"
                class="card-img-top" alt="ご友人紹介キャンペーン">



                <div class="card-body pt-5 text-center">
                    <h2 class="text-center fs-3 mb-3 fw-bold text-warning">ご友人紹介キャンペーン</h2>
                    <p class="text-secondary text-center fs-5 mb-0">

                        ご友人の「会員登録」と<br>「初回ポイント購入完了」後、<br><br>
                        紹介ユーザー様とご友人<br><br>
                        お二人にそれぞれ
                    </p>
                    <p class="fw-bold fs-3 text-warning text-center">
                        <strong class="fs-1">{{ $point }}</strong>ptプレゼント！！！
                    </p>
                </div>


                @if (Auth::check())
                    <section class="px-3 mb-5 pt-5 border-top">


                        <h5 class="text-center fs-5 fw-bold mb-3">{{Auth::user()->name.'様専用、'}}<br>ご友人紹介登録URL</h5>

                        <p class="text-secondary text-center fs-5 mb-0">
                            専用URLをコピーして、<br>
                            ご友人に会員登録してもらおう<br>
                        </p>

                        <div class="col-md-8 mx-auto">
                            <coppy-button-component copy_word="{{$url}}"></coppy-button-component>
                        </div>


                    </section>
                    <section class="px-3 mt- py-5 border-top">


                        {{-- <h5 class="text-center fs-5 mb-3">紹介登録URLを<br>X（旧twitter）でポストしよう！</h5>

                        <div class="col-md-8 mx-auto">
                            <a href="http://twitter.com/share?text={{Auth::user()->name.'様専用、ご友人紹介URL'}}&url={{$url}}" rel="nofollow"
                            class="btn btn-lg btn-dark text-white fs-3 w-100 rounded-pill" target="_blank"
                            >紹介URLをポスト</a>
                        </div> --}}

                        <h5 class="text-center fs-5 fw-bold mb-3">紹介登録URLをポストしよう！</h5>
                        @php
                        $sns_url  = $url;
                        $sns_text = config('app.name').'ご友人紹介キャンペーン!!紹介URLから「会員登録」と「初回ポイント購入完了」後、紹介ユーザー様とご友人に、それぞれ'.$point.'ptプレゼント！！';
                        @endphp
                        @include('includes.sns_btn')

                    </section>
                @else
                    <section class="px-3 mb-5 pt-5 border-top">

                        <h5 class="text-center fs-3 mb-3 text-primary">ログインはお済みですか？</h5>

                        <h5 class="text-center fs-5 mb-3">今すぐログインして、<br>ご友人紹介登録URLをゲットしよう！</h5>

                        <div class="col-md-8 mx-auto">
                            <a href="{{route('login')}}"
                            class="btn btn-lg btn-primary text-white fs-3 w-100 rounded-pill"
                            >ログイン/無料会員登録</a>
                        </div>


                    </section>
                @endif

            </div>

        </div>
    </div>
@endsection
