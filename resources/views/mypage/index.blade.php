{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','マイページ')


@section('content')
<!--breadcrumb-->
<div class="container mt-">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">マイページ</li>
        </ol>
    </nav>
</div>


<div class="container px-0 px-md-3 py-4 mb-5">
    <h3 class="d-none d-md-block ">マイページ</h3>


    <div class="mx-auto mt-4 bg-white rounded-3 overflow-hidden">
        <div class="row g-0">

            <div class="col-12 col-lg bg-dark">
                @include('mypage.menu01')
            </div>
            <div class="col-12 col-lg">
                @include('mypage.menu02')
            </div>
            <div class="col-12 col-lg-3">

                <div class="list-group list-group-flush">

                    <!--お友達紹介キャンペーン-->
                    @php
                    $canpaing_introductory_active = \App\Http\Controllers\CanpaingIntroductoryController::active();
                    @endphp
                    @if( $canpaing_introductory_active )
                        @php
                        # キャンペーン画像
                        $canpaing = new \App\Http\Controllers\CanpaingIntroductoryController;
                        $image_path = $canpaing::imagePath();
                        @endphp


                        <div class="list-group-item p-3 p-2">
                            <div class="row g-2">
                                <div class="col">
                                    <a href="{{route('canpaing.introductory')}}" class="d-block rounded-4 overflow-hidden">
                                        <ratio-image-component
                                        style_class="ratio ratio-4x3"
                                        url="{{ $image_path }}"
                                        ></ratio-image-component>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="list-group-item border-0 py-3">

                        <div class="fw-bold text-center mb-2">{{ config('app.name') }}をシェアする</div>
                        @include('includes.sns_btn')

                    </div>
                    <!-- フッターメニュー -->
                    <div class="list-group-item py-3 border-0 d-flex flex-column gap-3">


                        @include('includes.footer_menu')


                    </div>

                </div>

            </div>

        </div>
    </div>



    {{-- <div class="mx-auto mt-4 bg-white" style="max-width:400px;">

        <div class="offcanvas-body px-0 pt-0">


            @include('mypage.menu01')

            @include('mypage.menu02')

            <div class="list-group list-group-flush">

                <!--お友達紹介キャンペーン-->
                @php
                $canpaing_introductory_active = \App\Http\Controllers\CanpaingIntroductoryController::active();
                @endphp
                @if( $canpaing_introductory_active )
                    @php
                    # キャンペーン画像
                    $canpaing = new \App\Http\Controllers\CanpaingIntroductoryController;
                    $image_path = $canpaing::imagePath();
                    @endphp


                    <div class="list-group-item p-3 p-2">
                        <div class="row g-2">
                            <div class="col">
                                <a href="{{route('canpaing.introductory')}}" class="d-block rounded-4 overflow-hidden">
                                    <ratio-image-component
                                    style_class="ratio ratio-4x3"
                                    url="{{ $image_path }}"
                                    ></ratio-image-component>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="list-group-item border-0 py-3">

                    <div class="fw-bold text-center mb-2">{{ config('app.name') }}をシェアする</div>
                    @include('includes.sns_btn')

                </div>
                <!-- フッターメニュー -->
                <div class="list-group-item py-3 border-0 d-flex flex-column gap-3">


                    @include('includes.footer_menu')


                </div>

            </div>
        </div>

    </div> --}}

</div>
@endsection
