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


    @keyframes rotate {
        from {
            transform: scale(1.1) rotate(0deg) ;
        }
        to {
            transform: scale(1.1) rotate(360deg) ;
        }
    }
</style>
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
    w-100 pt-1 pb-3 text- border-top d-lg-none "
    style="z-index:50; background:rgb(255, 255, 255, .8);">
        <div class="container mx-auto" style="max-width:600px;">


            <!--在庫・価格-->
            <div id="discription-price"
            class="row align-items-center justify-content-center  g-3">

                <div class="col-auto ">
                    <button type="button"
                    onclick="history.back()"
                    style="width:2.2rem; height:2.2rem;"
                    class="btn btn-outline-secondary border-0 rounded-pill fs-5
                    d-flex align-items-center justify-content-center
                    "><i class="bi bi-chevron-left"></i><!--戻るボタン--></button>

                    <div class="text-secondary text-center fw-bold" style="font-size:11px;">戻る</div>
                </div>



                <div class="col ">
                    <div class="d-flex gap-2 justify-content-end mb-1" style="font-size:11px;">

                        <!--在庫-->
                        <div class=" bg-light border text-dark px-2 rounded-pill">
                            <span class="">残り</span>
                            {{number_format($gacha->remaining_count)}}
                        </div>

                        <!--待機中-->
                        <div class=" bg-warning px-2 rounded-pill">
                            <span class="">待機中</span>
                            {{number_format($gacha->waiting_count)}}
                        </div>

                    </div>


                    <!--ガチャボタン-->
                    <button class="btn btn-info text-white shadow rounded-pill
                    w-100
                    d-flex align-items-center justify-content-center gap-2 mx-auto"
                    data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
                    type="submit">
                        <i class="bi bi-arrow-repeat fs-3" style="line-height:.8rem;"></i>
                        ガチャを回す
                    </button>


                    <div class="position-relative h-100 bg-danger">

                        <div class="d-flex justify-content-center
                        position-absolute top-0 end-0
                        "
                        style="transform: scale(.5); "
                        >
                            <div class="">
                                <button class="btn btn-light p-0
                                border-info border-2 mx-auto
                                rounded-pill  rotate-hover
                                d-flex align-items-center justify-content-center
                                "
                                style="width: 8rem; height: 8rem;"
                                data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{$gacha->id}}"
                                type="submit">

                                    <div class="bg-info text-white p-1
                                    shadow rounded-pill w-100
                                    border border-white border-2
                                    d-flex align-items-center justify-content-center gap-2 mx-auto"
                                    style="line-height:.8rem;"
                                    >
                                        <i class="bi bi-arrow-repeat fs-2" ></i>
                                    </div>


                                </button>


                                <div class="fs-6 fw-bold text-center">ガチャを回す</div>
                            </div>
                        </div>

                    </div>


                </div>



                <div class="col-auto">
                    <!--価格-->
                    <div class="text-center">
                        <span style="font-size:11px;">１回</span>
                        <span style="font-size:11px;">(税込)</span>
                        <div  style="line-height:18px;">
                            <span class="fs-4 text-danger">¥</span>
                            <span class="fs-1 text-danger"> {{number_format($gacha->price)}}</span>
                        </div>
                    </div>

                </div>



            </div>

        </div>
    </div>


    <!--ポップアップモーダル-->
    @include('gacha.common.play_modal')

@endsection
