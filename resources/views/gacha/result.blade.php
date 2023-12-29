@extends('layouts.app')

<!--title-->
@section('title','「'.$gacha->name.'」の結果')


@section('meta')
    @php
        $meta_title = '「'.$gacha->name.'」の結果';
        $meta_image = asset('storage/site/image/gacha/bg_result.jpg');
    @endphp
@endsection


@section('style')
    <style>
        main{ padding-top: 0rem; }
        #result {
            background: no-repeat center center / cover;
            background-image: url({{asset('storage/site/image/gacha/bg_result.jpg')}});
        }
    </style>
@endsection



@section('content')
    <section id="result" style="padding-top:3rem; min-height: 80vh;">

        <div class="container px-3 py-4"  style="max-width:500px;">


            <h3 class="text-secondaryy fw-bold rounded-3 p-2 text-center w-100 mb-4"
            style="background: rgb(255, 255, 255, .0);"
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
                r_use_gacha_history_show="{{ route('api.use_gacha_history.show',$user_gacha_history) }}"
                ></u-gacha-result-form>

            </form>

        </div>

    </section>


    <!--ボトムメニュー-->
    <section class="position-fixed bottom-0 end-0 w-100 py-2 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container">

            @php
            $params = [
                'category_code'=>$gacha->category->code_name,
                'gacha'=>$gacha,
                'key'=>$gacha->key
            ];
            @endphp

            <div class="row g-3 justify-content-center">
                <div class="col-6 col-md-4">
                    <a href="{{ route('gacha', $params ) }}"
                    class="btn btn-light border-warning border-3 rounded-pill w-100 position-relative"
                    >{{ 'もう一度ガチャる' }}
                        <div class="position-absolute top-50 end-0 translate-middle-y p-1 text-warning"><i class="bi bi-chevron-right"></i></div>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('gacha_category', $gacha->category->code_name ) }}"
                    class="btn btn-light border-warning border-3 rounded-pill w-100 position-relative"
                    >{{ '他のガチャを選ぶ' }}
                        <div class="position-absolute top-50 end-0 translate-middle-y p-1 text-warning"><i class="bi bi-chevron-right"></i></div>
                    </a>
                </div>
            </div>


        </div>
    </section>

@endsection
