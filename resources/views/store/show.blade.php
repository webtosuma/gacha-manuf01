@extends('store.layouts.sub')

<!----- title ----->
@section('title',$store_item->name)


@section('style')
    <!-- splide css-->
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">
@endsection


@section('script')

    <!-- splide js -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>

    <script>
        document.addEventListener( 'DOMContentLoaded', function() {

            var splideStoerItemShow = new Splide( '#splide_store_item_show', {

                type   : 'splide',//ループしない
                rewind : false,   // 最後のスライドの後に最初に戻らないようにする
                focus  : 'start',
                perPage : 1,
                autoplay: false,
                pagination: true,
                padding: '0rem',

            } );
            splideStoerItemShow.mount();

        });
    </script>

@endsection


<!--meta-->
@section('meta')
    <!--ヘッダーの戻るボタン-->
    @php $header_back_btn = true; @endphp

    @php
    $meta_title       = $store_item->name.'-'.$store_item->category->name;
    $meta_description = $store_item->discription_text;
    $meta_image = $store_item->image_paths ? $store_item->image_paths[0] : '';
    @endphp
@endsection



@section('content')

    <!--ボトムメニュー-->
    {{-- @include('ticket_store.common.bottom_menu') --}}



    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$store_item->name}}</li>
            </ol>
        </nav>
    </div>



    <div class="container py-md-4 pb-5 mb-5 ">
        <div class="mx-auto" style="max-width:900px;">


            <div class="row gy-3 gx-5 mx-0">
                <div class="col-12 col-lg-6">

                    <!--image-->
                    @if( count($store_item->image_paths)<2 )

                        @include('store.section.common.image')

                    @else
                        <div id="splide_store_item_show" class="splide" aria-label="Splideの基本的なHTML">
                            @include('store.section.common.splide_store_item_show')
                        </div>
                    @endif


                    @if( $store_item->movie_path )
                        <!--動画-->
                        <div class="my-3">
                            <video
                            playsinline
                            controls
                            width="100%"
                            poster=""
                            >
                                <source src="{{$store_item->movie_path}}" />
                            </video>
                        </div>

                    @endif


                </div>
                <div class="col-12 col-lg-6">
                    <form action="{{route('store.purchase.appli')}}" method="POST">
                        @csrf
                        <!--ストアー商品ID-->
                        <input type="hidden" name="store_item_id" value="{{$store_item->id}}">


                        <div class="mb-3">

                            @if($store_item->new_label_path)
                                <!--NEW-->
                                <div class="text-white bg-danger px-2 d-inline-block mb-2">NEW</div>
                            @endif

                            <!--商品名-->
                            <div class="mb-2">
                                <h5 class="fs-4 mb-0">{{$store_item->name}}</h5>
                            </div>

                            <!--カテゴリー-->
                            <div class="mb-2">
                                <a href="{{route('store.search',[ 'category_code_name'=>$store_item->category->code_name ])}}"
                                class="btn btn-sm border text-secondary rounded-pill">{{$store_item->category->name}}</a>
                            </div>

                            <!--ブランド名-->
                            @if( $store_item->brand_name )
                                <div class="mb-2">
                                    <a  href="{{route('store.search',[ 'keyword'=>$store_item->brand_name ])}}"
                                    >{{$store_item->brand_name}}</a>
                                </div>
                            @endif
                        </div>


                        <!--価格-->
                        <div class="fs-2 mb-">
                            <span >¥</span>
                            {{number_format($store_item->price)}}
                            <span class="fs-6">(税込)</span>
                        </div>


                        <div class="d-flex justify-content-between">
                            <!--還元ポイント-->
                            <div class="fs-6 text-danger">
                                {{number_format($store_item->points_redemption)}}
                                <span class="fs-6">pt還元</span>
                            </div>

                            <!--在庫-->
                            <div class="fs-6">
                                <span class="fs-6">在庫</span>
                                {{number_format($store_item->count)}}
                                {{-- <span class="fs-6">点</span> --}}
                            </div>
                        </div>


                        <!--ボタングループ-->
                        @if( !$store_item->is_sold_out )

                            <u-store-item-keep-btn
                            max_count="{{$store_item->count}}"
                            r_api_keep="{{route('store.keep.api.keep',$store_item)}}"
                            default_disabled="{{$store_keep ? 1 : 0}}"
                            ></u-store-item-keep-btn>

                        @else
                            <div class="my-5 text-center text-danger fs-3">SOLD OUT</div>
                        @endif



                        <!--discription-->
                        @if($store_item->discription_text)
                            <p class="border-top py-3">
                                {!! str_replace(["\r\n","\r","\n"],"<br>", e( $store_item->discription_text ) )!!}<br>
                            </p>
                        @endif



                    </form>
                </div>
                <div class="col-12">


                    <!--シェアボタン-->
                    <div class="text-center my-5">
                        <h6>\この商品をシェア/</h6>
                        <div class="d-flex justify-content-center mt-3">
                            @php
                            $sns_url  = request()->url();
                            $sns_text = $store_item->name;
                            $sns_size = '2rem';
                            @endphp
                            @include('includes.sns_btn')
                        </div>
                    </div>


                    <div class="my-5">
                        <div class="col-md-6 mx-auto my-3">
                            <a href="#" onClick="history.back(); return false;"
                            class="btn btn-lg btn-light border rounded-pill w-100"
                            >戻る</a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
