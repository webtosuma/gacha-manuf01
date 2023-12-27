@extends('layouts.app')

<!----- title ----->
@section('title','お友達紹介キャンペーン')

@section('style')
    <style>
        /* カスタム背景 */
        body{
            background-image: url({{ asset( 'storage/'.'site/image/campaign_introductory/bg.jpg' ) }});
        }
    </style>
@endsection


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">お友達紹介キャンペーン</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    {{-- <h3>お友達紹介キャンペーン</h3> --}}
    <div class="mx-auto" style="max-width:900px;">
        {{-- <section class="rounded-4 overflow-hidden my-5">


            <ratio-image-component
            style_class="ratio ratio-4x3"
            url="{{ asset( 'storage/'.'site/image/campaign_introductory/index.jpg' ) }}"
            ></ratio-image-component>



        </section> --}}
        <section class="rounded-4 px-3 my-5 py-5 bg-white">


            <h2 class="text-center fs-1 mb-5 fw-bold text-warning">お友達紹介キャンペーン</h2>

            <p class="text-secondary text-center">
                お友達の会員登録＋初回ポイント購入完了後、<br>
                紹介ユーザー様とお友達に、それぞれ
            </p>
            <p class="fw-bold fs-3 text-warning text-center">
                <strong class="fs-1">1,000</strong>ptプレゼント！！！
            </p>


        </section>
        <section class="rounded-4 px-3 my-5 py-5 bg-white">


            <h5 class="text-center fs-2 mb-5">{{Auth::user()->name.'様専用、'}}<br>お友達紹介登録用URL</h5>

            <div class="col-md-8 mx-auto">
                <coppy-button-component copy_word="{{$url}}"></coppy-button-component>
            </div>


        </section>
        <section class="rounded-4 px-3 my-5 py-5 bg-white">


            <h5 class="text-center fs-2 mb-5">X（旧twitter）で<br>紹介登録URLをポストしよう！</h5>

            <div class="col-md-8 mx-auto">
                <a href="http://twitter.com/share?text={{Auth::user()->name.'様専用、お友達紹介URL'}}&url={{$url}}" rel="nofollow"
                class="btn btn-lg btn-primary text-white fs-3 w-100 rounded-pill" target="_blank"
                >紹介登録URLをポスト</a>
            </div>


        </section>

    </div>
</div>
@endsection
