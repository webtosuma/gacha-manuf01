@extends('manuf.layouts.app')

<!--title-->
@section('title',$gacha_title->name)


<!--meta-->
@section('meta')
    @php
    $meta_title = $gacha_title->name;
    $meta_image = $gacha_title->image_samune_path;
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



@section('script')
 @include('manuf.gacha.common.js')
@endsection




@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manuf') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$gacha_title->name}}</li>
            </ol>
        </nav>
    </div>



    <div class="container px-0">


        <div class="row mx-0 g-0 g-md-3">


            <!--flex-c2-1 -->
            <div class="col-12 col-lg-8">
                <div class="mx-auto" style="max-width:600px;">

                    @include('manuf.gacha.common.title_discription.index')

                </div>
            </div>

            <!--flex-c2-2 -->
            <aside class="col">
                <div class="position-sticky" style="top: 2rem; ">


                    <div class="p-3 rounded-4 mb-5" style="background:rgb(255, 255, 255, .0;">

                        <!--購入ボタン-->
                        @include('manuf.gacha.common.title_discription.purchase_button')
                    
                    </div>
                    

                    <!--note-->
                    @include('manuf.gacha.common.title_discription.note')



                </div>
            </aside>

            
        </div>
    </div>


    {{-- <div class="container px-0">
        <div class="row justify-content-center g-3 mx-0">
            <div class="col-12 col-lg-10 ">


                
                @if( env('SHARE_BTNS') )
                    <section class="list-group-item mb-5">
                        <div class="fw-bold text-center mb-2">このガチャをシェアする</div>

                        @php
                        $sns_url  = request()->url();
                        $sns_text = $gacha_title->name;
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
    </div> --}}





    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0
    w-100 py-2 shadow d-lg-none "
    style="z-index:50; background:rgb(255, 255, 255, 10);">
        <div class="container mx-auto" style="max-width:600px;">

            <!--購入ボタン-->
            @include('manuf.gacha.common.title_discription.purchase_button')

            {{-- @include('manuf.gacha.show.price_metter') --}}


        </div>
    </div>


    <!--ポップアップモーダル-->
    {{-- @include('gacha.common.play_modal') --}}

@endsection
