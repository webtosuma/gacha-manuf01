@extends('layouts.app')

<!--title-->
@section('title',$gacha->name.'-'.$gacha->category->name.'のガチャ')


<!--meta-->
@section('meta')
    @php
    $meta_title = $gacha->name.'-'.$gacha->category->name.'のガチャ';
    // $meta_description = "オンラインオリパ引くならcardFesta（カードフェスタ）! 高確率、爆アドガチャを多数ご用意しています。ポケカ・ワンピースなど人気オリパを24時間365日楽しめます。国内送料無料で、低コストガチャからハイリスクハイリターンなガチャなど楽しみ方は自由自在！ ";
    $meta_image = $gacha->image_path;
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
    <div class="position-fixed bottom-0 end-0 w-100 pb-3 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container mx-auto" style="max-width:900px;">

            <!--metter-->
            @php $metter_bg_color = ''; @endphp
            @include('gacha.common.metter')

            <!--play_buttons-->
            @include('gacha.common.play_buttons')

        </div>
    </div>


    @include('gacha.show.main')


    <!--注意事項ー-->
    <section class="py-5">
        <div class="container px-2 overflow-auto mx-auto" style="max-width:900px;">
            <div class="p-3" style="border-radius:1rem; background:rgb(255, 255, 255, .9);">

                <h6 class="border border-danger border-2 p-2 text-danger text-center">
                    お買い求め前に必ずお読み下さい。
                </h6>


                <!--注意事項-->
                @include('includes.notes')


            </div>
        </div>
    </section>


    <section class="list-group-item mb-5">

        <div class="fw-bold text-center mb-2">このガチャをシェアする</div>
        @php
        $sns_url  = request()->url();
        $sns_text = $gacha->name;
        @endphp
        @include('includes.sns_btn')

    </section>



    <!-- その他のガチャ情報 -->
    <div class="container my-5 mx-auto" style="max-width:900px;">

        @include('gacha.common.result_gachas')

    </div>

@endsection
