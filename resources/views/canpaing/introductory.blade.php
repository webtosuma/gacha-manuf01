@extends('layouts.sub')

<!----- title ----->
@section('title','ご友人紹介キャンペーン・ご紹介用URL')


@section('meta')
    @php
        $meta_title = 'ご友人紹介キャンペーン・ご紹介用URL';
        $meta_image = asset( 'storage/'.'site/image/campaign_introductory/index.png' );
    @endphp
@endsection

@section('style') @endsection


@section('content')

    <!--breadcrumb-->
    <div class="container mt-md-3 ">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">ご友人紹介キャンペーン</li>
            </ol>
        </nav>
    </div>


    <div class="container py-md-4 mb-5">
        {{-- <h3>ご友人紹介キャンペーン</h3> --}}
        <div class="mx-auto" style="max-width:600px;">


            @include('canpaing.introductory_card')

        </div>
    </div>
@endsection
