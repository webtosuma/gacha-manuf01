@extends('admin.layouts.app')

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
        #result{
            background: no-repeat center center / cover ;
            background-image: url({{ $bg_image }});
        }
    </style>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                >{{ 'ガチャ管理' }}</a></li>
                {{-- <li class="breadcrumb-item"><a href="{{ route('admin.gacha',$gacha->category_code_name) }}"
                >{{ $gacha->category->name }}</a></li> --}}
                <li class="breadcrumb-item active" aria-current="page">{{ $gacha->name }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』詳細情報</h2>


        <a href="{{route('admin.gacha',$gacha->category_code_name)}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>



        <!--タブメニュ-->
        @php $tab='admin.gacha.show'; @endphp
        @include('admin.gacha.common.tab')


        <div class="row mx-0 g-3">
            <!--flex-c2-->
            <div class="col bg-white bg_gacha rounded-3">

                <!--プレビュー-->
                <div class="col-10 mx-auto pt-4">
                    <section id="result" style="padding-top:3rem; min-height: 80vh;">
                        <div class="container px-3 py-4"  style="max-width:500px;">



                            <h3 class="p- mb-3 fs-6">
                                <div class="rounded-3 p-3 text-light" style="background: rgb(0, 0, 0, .7);">

                                    <div class="mb-2" style="font-size:10px;">
                                        <div class="">{{$user_gacha_history->created_at->format('Y/m/d H:i')}}</div>
                                    </div>


                                    <div class="mb-3" style="font-size:.8rem;">
                                        <div class="fs-5 text-center">{{$page_title}}</div>
                                    </div>
                                </div>
                            </h3>

                            <!--ポイント交換フォーム-->
                            @php
                            $params = [
                                'category_code'=>$gacha->category_code_name,
                                'user_gacha_history'=>$user_gacha_history->id
                            ];
                            @endphp

                            <form action="{{ route( 'admin.gacha.exchange_points', $params) }}" method="POST">
                                @csrf
                                @method('PATCH')


                                <!--カード一覧-->
                                <u-gacha-result-form
                                token="{{ csrf_token() }}"
                                r_api_use_gacha_history_show="{{ route('api.use_gacha_history.show',$user_gacha_history) }}"
                                r_gacha_category="{{ route('gacha_category',$gacha->category_code_name) }}"
                                show_change_btn="0"
                                ></u-gacha-result-form>


                            </form>


                        </div>
                    </section>
                </div>

            </div>
            <!--flex-c1-->
            <aside class="d-none d-lg-block col-4 ">
                <div class="position-sticky" style="top: 2rem; ">

                    @include('admin.gacha.common.data')


                </div>
            </aside>
        </div>

        <!--Modal-->
        @include('admin.gacha.common.custom_button_modal')

    </div>
@endsection
