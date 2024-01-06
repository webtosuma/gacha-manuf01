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
    body{
        background-image: url({{ $gacha->category->bg_image_path }});
    }
</style>
@endsection


@section('content')

    {{-- <section class="bg-dark" style="height:4.2rem;"></section> --}}

    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0 w-100 pb-3 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container mx-auto" style="max-width:600px;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center gap-2 fs-6">
                    @include('includes.point_icon')

                    <div class="">
                        1回×
                        <span class="fs-4">
                            <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                        </span>pt
                    </div>
                </div>
                <div class="progress">
                    @php
                    $ratio = $gacha->remaining_ratio;
                    $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
                    $style_class = 'progress-bar progress-bar-striped '.$bg_color
                    @endphp
                    <div class="{{ $style_class }}" role="progressbar"
                    style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"></div>
                </div>
                <p class="form-text text-white text-center m-0">
                    残り
                    <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                    /
                    <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                </p>

            </div>
            <div class="row g-2 mt-1">
                @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

                <div class="col-6">
                    <form action="{{ route('gacha.play', $params) }}" method="post">
                        @csrf

                        @if ($gacha->remaining_count >=1) {{--  --}}
                            <button type="submit" name="play_count" value="{{ 1 }}"
                            class="btn py-md-3 btn-light bg-gradient fw-bold w-100
                            rounded-pill border-secondary border-3"
                            >1回ガチャる</button>
                        @else
                            <button type="submit" name="play_count" disabled
                            class="btn py-md-3 btn-light bg-gradient fw-bold w-100 text-danger
                            rounded-pill border-secondary border-3"
                            >売り切れ</button>
                        @endif

                    </form>
                </div>


                <div class="col-6">
                    <form action="{{ route('gacha.play', $params) }}" method="post">
                        @csrf
                        @if ($gacha->remaining_count >=10) {{--  --}}
                            <button type="submit" name="play_count" value="{{ 10 }}"
                            class="btn py-md-3 btn-dark bg-gradient text- fw-bold w-100
                            rounded-pill border-danger border-3"
                            >10連ガチャる</button>
                        @else
                            <button type="submit" name="play_count" disabled
                            class="btn py-md-3 btn-dark bg-gradient text- fw-bold w-100 text-danger
                            rounded-pill border-secondary border-3"
                            >売り切れ</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>


    @include('gacha.show.main')


    <!--注意事項ー-->
    <section class="py-5">
        <div class="container px-0 overflow-auto mx-auto" style="max-width:600px;">
            <div class="p-3" style="border-radius:1rem; background:rgb(255, 255, 255, .9);">

                <h6 class="border border-danger border-2 p-2 text-danger text-center">
                    お買い求め前に必ずお読み下さい。
                </h6>


                <!--注意事項-->
                @include('includes.notes')


            </div>
        </div>
    </section>

@endsection
