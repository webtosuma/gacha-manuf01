@extends('layouts.app')

<!--title-->
@section('title','ガチャ詳細')

@section('style')
<style>
    /* サイトデフォルト背景 */
    body{
        /* background-image: url({{asset('storage/site/image/bg03.png')}}); */
        background-image: url({{ $gacha->category->bg_image_path }});


    }
</style>
@endsection


@section('content')

    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0 w-100 pb-3 text-white" style="background:rgb(0, 0, 0, .7)">
        <div class="container">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center gap-2 fs-5">
                    @include('includes.point_icon')

                    <div class="">
                        1回×<span class="fs-1">{{ $gacha->one_play_point }}</span>pt
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
                <p class="fs-5 text-center m-0">
                    {{ '残り'.$gacha->remaining_count.'/'.$gacha->max_count }}
                </p>

            </div>
            <div class="row g-2 mt-1">
                @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp

                <div class="col-6">
                    <form action="{{ route('gacha.play', $params) }}" method="post">
                        @csrf

                        @if ($gacha->remaining_count >=1) {{--  --}}
                            <button type="submit" name="play_count" value="{{ 1 }}"
                            class="btn btn-lg py-3 btn-light bg-gradient fw-bold w-100
                            rounded-pill border-secondary border-3"
                            >1回ガチャる</button>
                        @else
                            <button type="submit" name="play_count" disabled
                            class="btn btn-lg py-3 btn-light bg-gradient fw-bold w-100 text-danger
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
                            class="btn btn-lg py-3 btn-dark bg-gradient text- fw-bold w-100
                            rounded-pill border-secondary border-3"
                            >10連ガチャる</button>
                        @else
                            <button type="submit" name="play_count" disabled
                            class="btn btn-lg py-3 btn-dark bg-gradient text- fw-bold w-100 text-danger
                            rounded-pill border-secondary border-3"
                            >売り切れ</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!--トップー-->
    <section class="p- pb-md-5">
        <div class="mx-auto overflow-auto px-0" style="max-width:1200px;">


            <img class="d-block w-100 shadow" style="border-radius:1rem;"
            src="{{$gacha->image_path}}" alt="トップ画像">


        </div>
    </section>
    <!--各賞-->
    <div class="row justify-content-center mx-auto" style="max-width:1200px;">
        @foreach ($gacha->discriptions as $discription)
            <section class="py-5 col-12">

                <div class="container overflow-auto" style="max-width:600px;">

                    <!-- 賞ラベル -->
                    @switch( $discription->rank_id )
                        @case('XA')
                            <!--01 等賞ー-->
                            <div class="col-8 mx-auto mb-3">
                                <img class="d-block w-100"
                                src="{{asset('storage/site/image/gacha/01prize.png')}}" alt="1等賞">
                            </div>
                            @break
                        @case('XB')
                            <!--02 等賞ー-->
                            <div class="col-8 mx-auto mb-3">
                                <img class="d-block w-100"
                                src="{{asset('storage/site/image/gacha/02prize.png')}}" alt="2等賞">
                            </div>
                            @break
                        @case('XC')
                            <!--03 等賞ー-->
                            <div class="col-8 mx-auto mb-3">
                                <img class="d-block w-100"
                                src="{{asset('storage/site/image/gacha/03prize.png')}}" alt="3等賞">
                            </div>
                            @break
                        @case('XD')
                            <!--04 等賞ー-->
                            <div class="col-8 mx-auto mb-3">
                                <img class="d-block w-100"
                                src="{{asset('storage/site/image/gacha/04prize.png')}}" alt="4等賞">
                            </div>
                            @break
                    @endswitch

                    <!-- 景品画像 -->
                    @if ( $discription->image_path )
                        <img class="d-block w-100 shadow" style="border-radius:1rem;"
                        src="{{$discription->image_path }}" alt="景品画像">
                    @endif

                    <!-- 景品説明文 -->
                    @if ( $discription->sorce )
                    <p class="p-3 mt-2 form-text text-secondary" style="border-radius:1rem; background:rgb(255, 255, 255, .9);"
                    ><replace-text-component text="{{$discription->sorce_text }}"></replace-text-component></p>
                    @endif

                </div>
            </section>
        @endforeach
    </div>
    <!--注意事項ー-->
    <section class="py-5">
        <div class="container px-0 overflow-auto">
            <div class="p-3" style="border-radius:1rem; background:rgb(255, 255, 255, .9);">

                <h6 class="border border-danger border-2 p-2 text-danger text-center">
                    お買い求め前に必ずお読み下さい。
                </h6>

                <div class="p- py-3">
                    <h6 class="text-secondary">商品について</h6>
                    <ul class="form-text">
                        <li>
                            画像はイメージです。カードの状態を表すものではありません。
                        </li>
                        <li>
                            カードには一部、傷ありが出ることがあります。
                        </li>
                        <li>
                            お客様のご都合による、商品の交換・返金はできません。
                        </li>
                    </ul>

                    <h6 class="text-secondary">発送について</h6>
                    <ul class="form-text">
                        <li>
                            発送時期に関する個別のお問い合わせにはお答えできません。
                        </li>
                        <li>
                            ご入力いただいた住所の変更はできません。お間違えの無い様にご入力ください。
                        </li>
                        <li>
                            商品をお受け取りいただけなかった場合は、お客様ご自身で運送会社へお問い合わせください。
                        </li>
                        <li>
                            再配送の際は、配送料をお客様にご負担いただきますのでご了承ください。
                        </li>
                    </ul>

                    <h6 class="text-secondary">その他</h6>
                    <ul class="form-text">
                        <li>
                            アクセスが集中した場合、一時的にアクセスを制限させていただく場合がございます。しばらくお時間をおいてからアクセスしてください。
                        </li>
                    </ul>

                    <h6 class="text-secondary">お問い合わせについて</h6>
                    <ul class="form-text">
                        <li>
                            お問い合わせは、こちら
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}">{{ route('contact') }}</a>
                </div>

            </div>
        </div>
    </section>

@endsection
