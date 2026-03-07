@extends('manuf.layouts.app')

<!--title-->
@section('title',$gacha->name)


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

@endsection


@section('content')

    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manuf') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$gacha->name}}</li>
            </ol>
        </nav>
    </div>


    <div class="container px-0">

        @include('manuf.gacha.show.main')

    </div>
    <div class="container px-0">
        <div class="row justify-content-center g-3 mx-0">
            <div class="col-12 col-lg-10 ">


                <!--注意事項-->
                <div class="p-3 rounded-4 border mb-5" style="background:rgb(255, 255, 255, .9);">

                    <h6 class="border border-danger border-2 p-2 text-danger text-center">
                        必ずお読み下さい。
                    </h6>


                    @include('includes.notes')

                </div>

                @if( env('SHARE_BTNS') )
                    <section class="list-group-item mb-5">
                        <div class="fw-bold text-center mb-2">このガチャをシェアする</div>

                        @php
                        $sns_url  = request()->url();
                        $sns_text = $gacha->name;
                        @endphp
                        @include('includes.sns_btn')
                    </section>
                @endif



                <!-- その他のガチャ情報 -->
                <div class="container my-5 mx-auto" style="max-width:600px;">

                    @include('gacha.common.result_gachas')

                </div>




            </div>
        </div>
    </div>





    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0
    w-100 pt-1 pb-1 text- border-top d-lg-none "
    style="z-index:50; background:rgb(255, 255, 255, .8);">
        <div class="container mx-auto" style="max-width:600px;">


            @include('manuf.gacha.show.price_metter')


        </div>
    </div>


    <!--ポップアップモーダル-->
    @include('gacha.common.play_modal')

@endsection
