@extends('layouts.sub') 

<!--title-->
@section('title',$gacha->name.'-商品履歴')


<!--meta-->
@section('meta')
    @php
    $meta_title = $gacha->name.'-商品履歴';
    $meta_image = $gacha->image_path;
    $header_back_btn = true;//戻る
    @endphp
@endsection


@section('style')
<style>
    /* main{ padding-top: 0rem; } */

    /* サイトデフォルト背景 */
    #bgWindow{
        background-image: url({{ $gacha->category->bg_image_path }});
    }
</style>
@endsection


@section('content')


    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0 w-100 pb-3 text-white d-lg-none"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container mx-auto" style="max-width:600px;">

            @php
            $params = [ 'category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key ];
            @endphp
            <a href="{{ route( 'gacha', $params )         }}"
            class="btn btn-light fw-bold rounded-pill w-100 mt-3"
            >詳細を見る</a>

        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-12 col-lg" style="min-height:100vh;">

                <!--トップ画像-->
                <div class="overflow-hidden rounded-4"
                >
                {{-- data-aos="zoom-out" --}}

                    @include('gacha.common.top_image')


                </div>

                <h3 class="d-none d-md-block mt-3">{{$gacha->name.'-商品履歴'}}</h3>

                <u-gacha-prize-history
                token="{{ csrf_token() }}"
                r_api_list="{{route('gacha.api.prize_history',$gacha)}}"
                ></u-gacha-prize-history>




            </div>
            <div class="col-12 col-lg-4"  style="min-width: 360px;">
                <div class="position-sticky ps-2 mb-5" style="top: 4rem; ">


                    <div>
                        <a href="{{ route( 'gacha', $params )         }}"
                        class="btn btn-light shadow fw-bold rounded-pill w-100 mt-3"
                        >詳細を見る</a>
                    </div>



                    <!-- その他のガチャ情報 -->
                    <div class="container my-5 mx-auto" style="max-width:600px;">

                        @include('gacha.common.result_gachas')

                    </div>



                </div>
            </div>

        </div>
    </div>


@endsection
