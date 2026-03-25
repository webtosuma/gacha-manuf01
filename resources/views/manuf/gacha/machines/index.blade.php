@extends('manuf.layouts.app')

<!--title-->
@section('title',$gacha->name.'/ガチャマシン選択')


<!--meta-->
@section('meta')
    @php
    $meta_title = $gacha->name;
    $meta_image = $gacha->image_path;
    @endphp
@endsection


@section('style')

    <style>
        /* main{ padding-top: 0rem; } */

        /* サイトデフォルト背景 */
        #bgWindow{
            background-image: url({{ $bg_image }});
        }


        /* ホバーすると回転する */
        .rotate-hover:hover
        /* ,
        .rotate-hover:focus */
        {
            animation: rotate .4s linear infinite;
        }

        .rotate-hover
        {
            transform: rotate(-35deg) ;
        }

        @keyframes rotate {
            from {
                transform: scale(1.1) rotate(-35deg) ;
            }
            to {
                transform: scale(1.1) rotate(325deg) ;
            }
        }
    </style>

    @include('manuf.gacha.common.css')

    <!-- splide css-->
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">


@endsection


@section('script')

    <!-- splide js -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    {{-- <script src="{{ asset('js/splide.js') }}"></script> --}}
    <script>
        "use strict";
        /**
         * ==========================================
         *  スライダー(splide)　JS
         * ==========================================
        */
        document.addEventListener( 'DOMContentLoaded', function() {


            /* PC */
            var splidePc = new Splide( '#splide_mobile', {

                type     : 'loop',
                padding: '50px',
                focus  : 'center',
                perPage : 1, //3
                autoplay: true,

            } );
            splidePc.mount();

        } ) ;

    </script>
@endsection


@section('content')

    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manuf') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="">{{$gacha->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">ガチャマシン選択</li>
            </ol>
        </nav>
    </div>






    <div class="container px-0">
        <div class="col-12 col-lg-10 mx-auto mb-5 mp-5">


            <div class="p-3">
                <a href="{{$gacha->route}}"
                class="btn btn-light d-block text-start border border-radius rounded w-100" >
                    <div class="row g-3 align-items-center">
                        <div class="col-auto h-100">
                            <i class="bi bi-chevron-compact-left fs-5"></i>
                        </div>
                        <div class="col-4 col-lg-2">

                            @include('manuf.gacha.common.top_image')



                        </div>
                        <div class="col ">


                            <div class="">

                                <!--discription head-->
                                <div id="discription-head" class="mb-3">

                                    <!--badge link-->
                                    <div class="d-flex gap-2 mb-2">
                                        {{-- @if($gacha->new_label) --}}
                                            <!--NEW-->
                                            <div
                                            class="py-0 text-white bg-danger px-2 rounded-pill"
                                            style="font-size:11px;">NEW</div>
                                        {{-- @endif --}}

                                        <!--カテゴリー-->
                                        <div
                                        class="btn btn-sm py-0 bg-white border text-secondary rounded-pill"
                                        style="font-size:11px;"
                                        >{{$gacha->category->name}}</div>


                                    </div>


                                    <!--商品名-->
                                    <div class="">
                                        <h5 class="fs-6 fw-bold mb-0">{{$gacha->name}}</h5>
                                    </div>


                                </div>



                                <div class="d-flex justify-content-end">
                                    <div class="text-center">
                                        <span style="font-size:16px;">１回/</span>
                                        <span style="font-size:16px;">税込</span>
                                        <div class="d-inline-block" style="line-height:18px;">
                                            <span class="fs-3 text-danger">¥</span>
                                            <span class="fs-3 text-danger"> {{number_format($gacha->price)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </a>
            </div>


            <!--header-->
            <div class="p-3">

                <h5 class="fw-bold ps-1
                border-start border-info border-5 fs-4
                " >ガチャマシンを選ぶ</h5>

                <div class="
                position-relative
                bg-body text- border rounded form-text
                px-3 py-2 my-3">
                    <p class="m-0">
                        XXXXXXXX XXXXXXXX XXXXXXXX XXXXXXXX XXXXXXXX XXXXXXXX XXXXXXXX XXXXXXXX
                    </p>
                    <div class="
                    position-absolute top-0 translate-middle
                    " style="left:2rem;">
                        <div class="bg-body border-end border-bottom"
                        style="width:.8rem; height:.8rem; z-index:1; transform:rotate(225deg);"
                        ></div>
                    </div>
                </div>

            </div>


            @php $examples= [
                'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095777/melotabi_mejirushi_1.jpg',
                'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095784/melotabi_mejirushi_2.jpg',
                'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095791/melotabi_mejirushi_3.jpg',
            ]; @endphp



            <div id="splide_mobile"  class="splide d-md-none w-100" aria-label="Splideの基本的なHTML">

                <div class="splide__track">
                    <ul class="splide__list">
                        @for ($i = 1; $i < 6; $i++)
                            <li class="splide__slide px-2">


                                <div class="
                                bg-white p-3
                                w-100 text-start border shadow-sm
                                rounded-4 overflow-hidden "
                                >
                                    <!--head-->
                                    <div class="fw-bold text-center text-info fs-6">ガチャマシン09{{$i}}</div>

                                    <div class="ratio ratio-6x1 px- m-0 fs-6 fw-bold"
                                    style="
                                        background: no-repeat bottom center / contain;
                                        background-image:url( {{$gacha->img_path_card_head}} );
                                    " ></div>


                                    <!--body-->
                                    <div class="col-12">
                                        <div class="bg-white border mx-2">
                                            @include('manuf.gacha.common.top_image')
                                        </div>
                                    </div>




                                    <!--btn-->
                                    <div class="px-3 ratio ratio-1x1"
                                    style="
                                        background:no-repeat top center / cover rgba(255, 255, 255, 1);
                                        background-image: url( {{$gacha->img_path_card_body}} );
                                    " >
                                        <div class="row g-1 px- pt-2 text-center " style="font-size:14px;">

                                            <!--在庫-->
                                            <div class="col col-md">
                                                <div class=" bg-dark border text-white px-2 rounded w-100 ">
                                                    <span class="">残り</span>
                                                    {{number_format($gacha->remaining_count)}}
                                                </div>
                                            </div>

                                            <!--待機中-->
                                            <div class="col col-md">
                                                <div class=" bg-warning px-2 rounded w-100 ">
                                                    <span class="">待機中</span>
                                                    {{number_format($gacha->waiting_count)}}
                                                </div>
                                            </div>

                                            <!--gachaCustomModal-->
                                            <div class="col-12">
                                                <button class="text-center text-info bg-white border border-info fw-bold py-2
                                                border-3 rounded-pill shadow w-100
                                                hover_anime "
                                                data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
                                                type="button"
                                                >ガチャを回す</button>
                                            </div>

                                            <div class="col-12 text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a
                                                    data-bs-toggle="offcanvas" href="#oc_prizes{{$i}}" role="button" aria-controls="oc_prizes{{$i}}"
                                                    class="
                                                    btn btn-sm btn-info text-white py-0
                                                    border border-3 border-white rounded-pill shadow fw-bold
                                                    d-flex align-items-center justify-content-center flex-column
                                                    " style="width:3.6rem; height:3.6rem; font-size:11px; line-height:8px;">
                                                        <i class="bi bi-info-lg fs-3"></i><br>
                                                        詳細
                                                        {{-- を<br>見る --}}
                                                        {{-- <i class="bi bi-arrow-right fs-4"></i> --}}
                                                    </a>

                                                </div>
                                            </div>


                                        </div>

                                        {{-- <div class="mt-3">
                                            <div class="d-flex justify-content-center mb-4">
                                                <div class="ratio ratio-1x1 " style="width:40%;">
                                                    <div class="btn btn-light p-0
                                                    border-info border-4 mx-auto
                                                    rounded-pill  rotate-hover
                                                    d-flex align-items-center justify-content-center
                                                    w-100 h-100
                                                    "
                                                    data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
                                                    type="submit">

                                                        <div class="bg-info text-white p-1
                                                        shadow rounded-pill w-100
                                                        border border-white border-2
                                                        d-flex align-items-center justify-content-center gap-2 mx-auto"
                                                        style="height:20%;"
                                                        >
                                                            <i class="bi bi-arrow-repeat fs-4" style="line-height:.8rem;"></i>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}


                                    </div>


                                </div>



                            </li>
                        @endfor
                    </ul>
                </div>

            </div>



            <div class="d-none d-md-block">
                <div class="row g-4 ">
                    @for ($i = 1; $i < 6; $i++)
                    <div class="col-12 col-sm-6 col-lg-4">


                        <div
                        class="
                        bg-white p-3
                        w-100 text-start border shadow-sm
                        rounded-4 overflow-hidden "
                        >
                            <!--head-->
                            <div class="fw-bold text-center text-info fs-6">ガチャマシン09{{$i}}</div>

                            <div class="ratio ratio-6x1 px- m-0 fs-6 fw-bold"
                            style="
                                background: no-repeat bottom center / contain;
                                background-image:url( {{$gacha->img_path_card_head}} );
                            " ></div>


                            <!--body-->
                            <div class="col-12">
                                <div class="bg-white border mx-2">
                                    @include('manuf.gacha.common.top_image')
                                </div>
                            </div>




                            <!--btn-->
                            <div class="px-3 ratio ratio-1x1"
                            style="
                                background:no-repeat top center / cover rgba(255, 255, 255, 1);
                                background-image: url( {{$gacha->img_path_card_body}} );
                            " >
                                <div class="row g-1 px- pt-2 text-center " style="font-size:14px;">

                                    <!--在庫-->
                                    <div class="col col-md">
                                        <div class=" bg-dark border text-white px-2 rounded w-100 ">
                                            <span class="">残り</span>
                                            {{number_format($gacha->remaining_count)}}
                                        </div>
                                    </div>

                                    <!--待機中-->
                                    <div class="col col-md">
                                        <div class=" bg-warning px-2 rounded w-100 ">
                                            <span class="">待機中</span>
                                            {{number_format($gacha->waiting_count)}}
                                        </div>
                                    </div>

                                    <!--gachaCustomModal-->
                                    <div class="col-12">
                                        <button class="text-center text-info bg-white border border-info fw-bold py-2
                                        border-3 rounded-pill shadow w-100
                                        hover_anime "
                                        data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
                                        type="button"
                                        >ガチャを回す</button>
                                    </div>

                                    <div class="col-12 text-end">
                                        <div class="d-flex justify-content-end">
                                            <a
                                            data-bs-toggle="offcanvas" href="#oc_prizes{{$i}}" role="button" aria-controls="oc_prizes{{$i}}"
                                            class="
                                            btn btn-sm btn-info text-white py-0
                                            border border-3 border-white rounded-pill shadow fw-bold
                                            d-flex align-items-center justify-content-center flex-column
                                            " style="width:3.6rem; height:3.6rem; font-size:11px; line-height:8px;">
                                                <i class="bi bi-info-lg fs-3"></i><br>
                                                詳細
                                                {{-- を<br>見る --}}
                                                {{-- <i class="bi bi-arrow-right fs-4"></i> --}}
                                            </a>

                                        </div>
                                    </div>


                                </div>

                                {{-- <div class="mt-3">
                                    <div class="d-flex justify-content-center mb-4">
                                        <div class="ratio ratio-1x1 " style="width:40%;">
                                            <div class="btn btn-light p-0
                                            border-info border-4 mx-auto
                                            rounded-pill  rotate-hover
                                            d-flex align-items-center justify-content-center
                                            w-100 h-100
                                            "
                                            data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
                                            type="submit">

                                                <div class="bg-info text-white p-1
                                                shadow rounded-pill w-100
                                                border border-white border-2
                                                d-flex align-items-center justify-content-center gap-2 mx-auto"
                                                style="height:20%;"
                                                >
                                                    <i class="bi bi-arrow-repeat fs-4" style="line-height:.8rem;"></i>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div> --}}


                            </div>


                        </div>



                    </div>
                    @endfor
                </div>
            </div>









            <div class="col-md-8 mx-auto my-5 px-3">
                <button type="button"
                onclick="history.back()"
                class="
                btn btn-light border rounded-pill mt-3 w-100
                ">
                    <span class="">タイトルに戻る</span>

                </button>
            </div>


        </div>
    </div>







    <!--ポップアップモーダル-->
    @include('gacha.common.play_modal')

@endsection
