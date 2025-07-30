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


    <div class="container">
        <div class="row">
            <div class="col-12 col-lg ">

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





                {{-- <div class="row gy-3 my-5">
                    @foreach ($user_prizes as $user_prize)
                    <div class="col-auto">
                        <div class="form-text">{{$user_prize->created_at->format('Y/m/d h:i')}}</div>
                        <ratio-image-component
                        style_class="ratio ratio-3x4 rounded-3"
                        url="{{$user_prize->prize->image_path}}"
                        ></ratio-image-component>
                        <div class="d-flex align-items-center">
                            <div style="width: 18px">
                                <ratio-image-component
                                style_class="ratio ratio-1x1 rounded-pill border"
                                url="{{ $user_prize->user->image_path }}"
                                ></ratio-image-component>
                            </div>

                            <span class="mt-1">{{mb_substr($user_prize->user->name,0,8).'...'}}</span>
                        </div>
                    </div>
                    @endforeach
                </div> --}}


            </div>
            <div class="col-12 col-lg-4"  style="min-width: 360px;">
                <div class="position-sticky ps-2 mb-5" style="top: 4rem; ">



                    <!-- その他のガチャ情報 -->
                    <div class="container my-5 mx-auto" style="max-width:600px;">

                        @include('gacha.common.result_gachas')

                    </div>



                </div>
            </div>

        </div>
    </div>


@endsection
