@extends('layouts.app')

<!--title-->
@section('title',$gacha->name.'のガチャ回数カスタム-'.$gacha->category->name.'のガチャ')


<!--meta-->
@section('meta')
    @php
    $meta_title = $gacha->name.'のガチャ回数カスタム-'.$gacha->category->name.'のガチャ';
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


    <!-- ガチャ情報 -->
    <div class="container mb-5"  style="max-width:500px;">


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



        @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
        <form action="{{ route('gacha.play', $params) }}" method="post" class="mt-3">
            @csrf

            <h5 class="modal-title text-center form-text" id="{{'gachaModalLabel'.$gacha->id}}">
                <p>回数を指定して、<br>「ガチャる」ボタンを押してください。</p>


                @if ($gacha->type=='max_custom')
                    <!--上限ありのとき-->
                    <div class="badge fs-6 bg-danger">最大上限{{number_format( $gacha->max_custom_count() )}}回まで一括で回すことができます。</div>
                @endif
            </h5>
            <select name="play_count"
            class="form-select form-select-lg mb-3"  aria-label="Default select example">


                @php
                /* カスタムに上限があるとき */
                $max_count = $gacha->type=='max_custom' ? $gacha->max_custom_count() : $gacha->remaining_count;
                @endphp

                @for ($num = 1; $num <= $max_count; $num++)
                    <option value="{{ $num }}">{{ $num.'回ガチャる' }}</option>
                @endfor


            </select>
            <div class="row g-2">
                <div class="col-6">
                    <a href="#"
                    class="btn btn-light border rounded-pill w-100"
                    onclick="window.history.back();">キャンセル</a>
                </div>
                <div class="col-6">
                    <button type="submit"
                    class="btn btn-info text-white rounded-pill w-100"
                    >ガチャる</button>
                </div>
            </div>
        </form>


    </div>


    {{-- <section class="list-group-item mb-5">

        <div class="fw-bold text-center mb-2">このガチャをシェアする</div>
        @php
        $sns_url  = request()->url();
        $sns_text = $gacha->name;
        @endphp
        @include('includes.sns_btn')

    </section> --}}



    <!-- その他のガチャ情報 -->
    <div class="container my-5 mx-auto" style="max-width:900px;">

        @include('gacha.common.result_gachas')

    </div>

@endsection
