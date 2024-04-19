@extends('layouts.app')

<!--title-->
@section('title','「'.$gacha->name.'」の結果')


@section('meta')
    @php
        $meta_title = '「'.$gacha->name.'」の結果';
        $meta_image = $bg_image;
    @endphp
@endsection


@section('style')
    <style>
        main{ padding-top: 0rem; }
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
        /* body{
            background-image: url({{ $bg_image }});
        } */
    </style>
@endsection



@section('content')
    <section id="result" style="padding-top:3rem; min-height: 80vh;">
        <div class="container px-3 py-4"  style="max-width:500px;">


            <h3 class="text-secondaryy fw-bold rounded-3 p-2 text-center w-100 mb-4"
            >ガチャ結果</h3>

            <!--ポイント交換フォーム-->
            @php $params = [
                'category_code'=>$gacha->category->code_name,
                'user_gacha_history'=>$user_gacha_history
            ]; @endphp
            <form action="{{ route( 'gacha.exchange_points', $params) }}" method="POST">
                @csrf
                @method('PATCH')

                <!--カード一覧-->
                <u-gacha-result-form
                token="{{ csrf_token() }}"
                r_api_use_gacha_history_show="{{ route('api.use_gacha_history.show',$user_gacha_history) }}"
                r_gacha_category="{{ route('gacha_category',$gacha->category->code_name) }}"

                ></u-gacha-result-form>

            </form>


        </div>
    </section>
    <section class="mb-5">
        {{-- <div class="container px- py-"  style="max-width:500px;">


            <h5 class="fw-bold text-center mb-">ガチャ情報</h5>

            @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
            <a href="{{route('gacha',$params)}}"
            class="card border-secondary border-0 shadow bg-white
            text-dark text-center overflow-hidden text-decoration-none
            hover_anime" style="border-radius:1rem;">


                <!--image-->
                @include('gacha.common.top_image')

                <!--metter-->
                @include('gacha.common.metter')

            </a>

        </div> --}}
        <div class="mb-5 py-">


            <h5 class="text-center fs-5 fw-bold mb-3">ガチャ結果を送る</h5>
            @php
            $sns_url  = route('gacha.result_history',$user_gacha_history->key);
            $sns_text = $page_title
            @endphp
            @include('includes.sns_btn')


        </div>
        <!-- その他のガチャ情報 -->
        <div class="container px- pb-5"  style="max-width:500px;">

            @include('gacha.common.result_gachas')

        </div>

    </section>


    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0 w-100 py-3 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container mx-auto" style="max-width:600px;">
            <div class="card-body">


                <h5 class="text-center text-white m-0">もう一度ガチャる？</h5>


            </div>

            <!--play_buttons-->
            @include('gacha.common.play_buttons')


        </div>
    </div>


@endsection
