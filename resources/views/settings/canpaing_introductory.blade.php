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
          <li class="breadcrumb-item"><a href="{{ route('settings') }}">会員情報設定</a></li>
          <li class="breadcrumb-item active" aria-current="page">お友達紹介キャンペーン</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    {{-- <h3>お友達紹介キャンペーン</h3> --}}
    <div class="mx-auto" style="max-width:900px;">
        <section class="rounded-4 overflow-hidden my-5">


            <ratio-image-component
            style_class="ratio ratio-4x3"
            url="{{ asset( 'storage/'.'site/image/campaign_introductory/index.jpg' ) }}"
            ></ratio-image-component>



        </section>
        <section class="rounded-4 px-3 my-5 py-5" style="background:rgb(0, 0, 0,.3);">


            <h5 class="text-center fs-1 mb-5">お友達紹介登録用URL</h5>

            <div class="col-md-8 mx-auto">
                <coppy-button-component copy_word="{{$url}}"></coppy-button-component>
            </div>


        </section>
        <section class="rounded-4 px-3 my-5 py-5" style="background:rgb(0, 0, 0,.3);">


            <h5 class="text-center fs-1 mb-5">X（旧twitter）で<br>紹介登録URLをポストしよう！</h5>

            <div class="col-md-8 mx-auto">
                <a href="http://twitter.com/share?text={{  'お友達紹介登録用URL' }}&url={{$url}}" rel="nofollow"
                class="btn btn-lg btn-primary text-white fs-3 w-100 rounded-pill" target="_blank"
                >紹介登録URLをポスト</a>
            </div>


        </section>

    </div>
</div>
@endsection
