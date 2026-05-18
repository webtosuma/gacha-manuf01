@extends('manuf.layouts.app')


<!--title-->
@section('title')
    @php
    $metas = \App\Models\Text::getMeta();//DB登録情報
    // $title = $category_code=='all' || !isset($category_name) ? $metas['title'] : $category_name.'のガチャ一覧';
    $title = $metas['title'];
    @endphp

    {{$title}}
@endsection



<!--meta-->
@section('meta')
    @php
    // $meta_title = $category_code=='all' ? null : $category_name.'のガチャ一覧';
    @endphp
@endsection



<!--style-->
@section('style')
    <style>
        /* サイトデフォルト背景 */
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
    </style>

    @include('manuf.gacha.common.css')


    <!-- rainbow-css-->
    @include('gacha.common.rainbow-css')


    <!-- splide css-->
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">

    <style>
        /* ガチャのホバーアニメーション */
        .hover_anime:hover{
            position: relative;
            transform: scale(.97) translateY(0rem);
            opacity: .7;
            transition: all .2s;
        }
    </style>


@endsection



@section('script')

    <!-- splide js -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    {{-- <script src="{{ asset('js/splide.js') }}"></script> --}}
    
    <script>
        "use strict";
        document.addEventListener( 'DOMContentLoaded', function() {

            var splideMobile = new Splide( '#splide_mobile', {

                type     : 'loop',
                padding: '0',
                focus  : 'center',
                perPage : 1, //1
                autoplay: true,

            } );
            splideMobile.mount();

        } );
    </script>

@endsection



@section('content')

    {{-- <section class="container " 
    data-aos="fade-in"
    >
        <div class="bg- rounded-5 shadowX my-3">
            <img src="{{asset('storage/site/image/home_top.png')}}"
            alt="{{ config('app.name') }}" class="d-brock w-100"
            style="">  
        </div>
    </section> --}}



    <!--スライダー-->
    <section class="container overflow-hidden bg-rainbow-index mt-3"
    data-aos="fade-in"
    >
        @if( count($slides)>1 )
            <div class="col-10 mx-auto">
                <div id="splide_mobile" class="splide " aria-label="Splideの基本的なHTML">
                    @include('manuf.gacha.section.common.splide')
                </div> 
            </div>
        @else
            @php $slide = $slides[0];@endphp

            <a href="{{ $slide['href'] }}"
            class="ratio {{config('app.info_ratio')}}">
                <ratio-image-component
                style_class="ratio {{config('app.info_ratio')}} rounded-4"
                url="{{ $slide['image'] }}"
                bg_size="contain"
                ></ratio-image-component>    
            </a>
        @endif
    </section>





    <!--カテゴリー-->
    @if($categories->count()>1)
        <div class="col-md-10 mx-auto text-white">
            @include('manuf.gacha.section.category.index')
        </div>
    @endif


    <!--ガチャ-->
    <section class="container py-3 pb-5 " style="min-height:80vh;"
    data-aos="fade-in"
    >

        {{-- <div class="row g-2 mb-4 justify-content- ">
            <div class="col-12">
                <h5 class="fw-bold">発送予定から選択</h5>
            </div>

            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-info rounded-pill
                text-white fs-6
                " style="width:6rem; height:6rem;">
                    <span>すべて</span>
                </button>
            </div>
            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-warning rounded-pill
                text- fs-6
                " style="width:6rem; height:6rem;">
                    <span>XX月<br>発送予定</span>
                </button>
            </div>
            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-warning rounded-pill
                text- fs-6
                " style="width:6rem; height:6rem;">
                    <span>XX月<br>発送予定</span>
                </button>
            </div>
            <div class="col-auto">
                <button type="button"
                class="
                d-flex align-items-center justify-content-center
                btn btn-success rounded-pill
                text-white fs-6
                " style="width:6rem; height:6rem;">
                    <span>すぐ発送</span>
                </button>
            </div>

        </div> --}}


        <div class="row g-5 justify-content-center">
            @foreach ($gacha_title_sections as $key => $section )
                @if($section['data']->count()>0)
                    <div class="col-12 {{$key!='all'?'col-lg-6':'col-lg-10'}}">
                        

                        <!-- ラベル -->
                        <h3 class="bg- mb-3
                        py-2  bg- text-white
                        border border-white border-5
                        d-flex align-items-center rounded-4
                        w-100
                        " style=" 
                            border-radius: 0 0 0 0;
                        "><div class="d-flex align-items-center justify-content-between px-3 w-100">

                            <div class="d-flex gap-2 align-items-center">
                                <i class="bi fs-1 {{ $section['icon'] }}" ></i>
                                <span class="fs-3 fw-bold"
                                style="letter-spacing: 0.3em;"
                                >{{ $section['label'] }}</span>    
                            </div>

                            <div class="">
                                @if ( $section['link'] )
                                    <a href="{{ $section['link'] }}" class="btn btn-link fs-6 text-white px-0 hover_anime">
                                        <div class="d-flex align-items-center gap-2">
                                            <span>もっと見る</span>
                                            <i class="bi bi-chevron-right fs-4"></i>
                                        </div>
                                    </a>
                                @endif
                            </div>

                        </div></h3>



                        <div class="row g-3 justify-content-center gy-4">
                            @foreach ($section['data'] as $gacha_title)

                                @if( ! $gacha_title->is_published )<!--非公開は表示しない--> @continue  @endif

                                {{-- @if( ! $gacha_title->is_published )<!--非公開は表示しない--> @continue  @endif --}}


                                <div class="col-6 col-sm-3 {{$key!='all'?'col-lg-4':'col-lg-auto'}}" 
                                style="max-width:200px;">
                                    <a href="{{$gacha_title->r_show}}" 
                                    class="btn p-0 w-100  hover_anime mx-auto"
                                    
                                    >
                                        @php $machine_text = null; @endphp
                                        @include('manuf.gacha.common.machine_icon')        


                                    </a>
                                </div>
                            @endforeach
                        </div>


                    </div>
                @endif
            @endforeach
        </div>


    </section>



    <!--お知らせ-->
    @include('manuf.gacha.section.infomation')



@endsection
