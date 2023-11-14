@extends('layouts.app')

<!--title-->
@section('title','タイトル')

<!--meta-->
@section('meta')
@endsection

<!--style-->
@section('style')
<style>
    /* サイトデフォルト背景 */
    body{
        background-image: url({{ $bg_image }});
    }
</style>
<style>
    /* カルーセル */
    .carousel-indicators [data-bs-target] {
        border-radius: 100%;
        widows: 30px; height: 30px;
        margin-bottom:0;
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        display: inline-block;
        width:  4rem;
        height: 4rem;
        background-repeat: no-repeat;
        background-position: 50%;
        background-size: 100% 100%;
    }

    .carousel-control-prev, .carousel-control-next {
        width: 3rem;
    }


    /* タブメニュー */
    .nav-link{
        color: rgb(33,37,41);
        font-size: 1.25rem
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: #fff;
        background-color:rgb(33,37,41) !important;
    }

    /* ガチャのホバーアニメーション */
    .hover_anime:hover{
        position: relative;
        transform: scale(1.05) translateY(-1rem);

        transition: all .2s;
    }

</style>
@endsection


@section('content')
    <!--カルーセル-->
    <section class="bg-dark overflow-hidden" style="
    background: url({{asset('storage/site/image/bg02.jpg')}}) no-repeat center center/cover;
    ">
        <div class="container px-0" style="">
            <div class="col-md-8 mx-auto py-">
                <div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">

                    <!--image-->
                    <div class="carousel-inner anm_opasity_e01" style="max-height:90vh;">
                        @foreach ($gachas as $gi => $gacha)

                            @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
                            <a href="{{ route('gacha',$params) }}" class="carousel-item pb- bg-dark
                            {{ $gi==0 ? 'active' : ''}}">

                                <ratio-image-component
                                style_class="ratio ratio-4x3"
                                url="{{ $gacha->image_path }}"
                                ></ratio-image-component>

                            </a>
                        @endforeach
                    </div>


                    <!--side menu-->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                    </button>


                    <!--bottom menu-->
                    <div class="carousel-indicators mb-0">
                        @foreach ($gachas as $gi => $gacha)
                            <button type="button" data-bs-target="#carouselIndicators"
                            class="{{ $gi==0 ? 'active' : ''}}"
                            data-bs-slide-to="{{$gi}}" aria-current="true" aria-label="{{'Slide '.($gi+1)}}x"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--お知らせ-->
    {{-- <section class="bg-dark">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('お知らせ') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <a href="{{route('payment')}}">{{ __('ポイント購入') }}</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!--カテゴリー-->
    <section class="p-3 bg-dark">
        <div class="container overflow-auto">
            <nav class="nav gap-3 flex-nowrap" style="min-width:900px;">
                @php
                $sc = "col-md fs-5 fw-bold btn btn-light rounded-pill border-dark border-2";
                $style_class = $category_code=='all' ? $sc.' disabled' : $sc;
                @endphp
                <a  href="{{ route('gacha_category','all') }}"
                class="{{ $style_class }}">{{ 'すべて' }}</a>


                @foreach ($categories as $category)
                    @php
                    $style_class = $category_code == $category->code_name ? $sc.' disabled' : $sc;
                    @endphp

                    <a  href="{{ route('gacha_category', $category->code_name ) }}"
                    class="{{ $style_class }}">{{ $category->name }}</a>
                @endforeach
            </nav>
        </div>
    </section>
    <!--ガチャ-->
    <section class="p-3 pb-5">
        <div class="container">

            <!--card-->
            <div class="row gy-5 my-3 overflow-hidden">
                @foreach ($gachas as $gacha)
                    <div class="col-12 col-md-6 col-lg-4 ">

                        @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
                        <a href="{{route('gacha',$params)}}"
                        class="card border-secondary border-3 shadow bg-white
                        text-dark text-center overflow-hidden text-decoration-none
                        hover_anime" style="border-radius:1rem;">


                            <!--image-->
                            <div class="position-relative">
                                <ratio-image-component
                                url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3"
                                ></ratio-image-component>

                                @if ($gacha->remaining_count==0)
                                <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="z-index:10; background: rgba(0, 0, 0, .7);"
                                ><div class="d-flex align-items-center justify-content-center h-100 fs-1 text-white"
                                >売り切れました</div></div>
                                @endif
                            </div>

                            <!--metter-->
                            <div class="card-body">
                                <h6 class="d-flex justify-content-between align-items-end">
                                    <p class="card-text m-0">
                                        {{ '残り'.$gacha->remaining_count.'/'.$gacha->max_count }}
                                    </p>

                                    <div class="d-flex align-items-center gap-2">
                                        @include('includes.point_icon')

                                        <div class="">
                                            1回×<span class="fs-1">{{ $gacha->one_play_point }}</span>pt
                                        </div>
                                    </div>
                                </h6>
                                <div class="progress">
                                    @php
                                    $ratio = $gacha->remaining_ratio;
                                    $bg_color = $ratio>70 ? 'bg-primary' : ( $ratio>40 ? 'bg-warning' : 'bg-danger' );
                                    $style_class = 'progress-bar progress-bar-striped '.$bg_color
                                    @endphp
                                    <div class="{{ $style_class }}" role="progressbar"
                                    style="width: {{$ratio.'%'}}" aria-valuenow="{{ $ratio }}" aria-valuemin="0" aria-valuemax="{{ $ratio }}"></div>
                                </div>
                            </div>
                        </a>


                        <div class="row g-2 mt-1">
                            @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

                            <div class="col-6">
                                <form action="{{ route('gacha.play', $params) }}" method="post">
                                    @csrf

                                    @if ($gacha->remaining_count >=1) {{--  --}}
                                        <button type="submit" name="play_count" value="{{ 1 }}"
                                        class="btn btn-light bg-gradient fw-bold w-100
                                        rounded-pill border-secondary border-3"
                                        >1回ガチャる</button>
                                    @else
                                        <button type="submit" name="play_count" disabled
                                        class="btn btn-light bg-gradient fw-bold w-100 text-danger
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
                                        class="btn btn-dark bg-gradient text- fw-bold w-100
                                        rounded-pill border-secondary border-3"
                                        >10連ガチャる</button>
                                    @else
                                        <button type="submit" name="play_count" disabled
                                        class="btn btn-dark bg-gradient text- fw-bold w-100 text-danger
                                        rounded-pill border-secondary border-3"
                                        >売り切れ</button>
                                    @endif

                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </section>

@endsection
