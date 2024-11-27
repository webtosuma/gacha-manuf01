@extends('layouts.app')

<!--title-->
@section('title',$page_title)


@section('meta')
    @php
        $meta_title = $page_title;
        $meta_image = $gacha->image_path;
    @endphp
@endsection


@section('style')
    <style>
        main{ padding-top: 0rem; }
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
        .ratio-3x4{
            --bs-aspect-ratio: 133.3%;
        }
    </style>
@endsection



@section('content')
    <section id="result" style="padding-top:3rem; min-height: 80vh;">
        <div class="container px-3 py-4"  style="max-width:500px;">


            <!-- PLAY情報 -->
            <h2 class="p- mb-3 fs-6">
                <div class="rounded-3 p-3 text-light" style="background: rgb(0, 0, 0, .7);">
                    <div  data-aos="fade-in">

                        <div class="mb-2" style="font-size:10px;">
                            <div class="">{{$user_gacha_history->created_at->format('Y/m/d H:i')}}</div>
                        </div>

                        <div class="mb-3">
                            <div class="row align-items-center g-2">

                                <div class="col">
                                    <div class="fs-">{{ $user->name }} さん</div>
                                </div>


                            </div>
                        </div>

                        <div class="mb-3" style="font-size:.8rem;">
                            <div class="fs-5">{{$page_title}}</div>
                        </div>

                    </div>
                </div>
            </h2>


            <!--商品一覧-->
            <ul class="row justify-content-center align-items-center ps-0 g-2 gy-4 mb-4"
            style="list-style:none; min-height:50vh;">

                @forelse ($user_prizes as $user_prize)
                <li  class="{{ $user_prizes->count()==1? 'col-6' : 'col-3' }}">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="w-100"
                        data-aos="zoom-in"
                        >



                            <div class="position-relative">
                                <!--loading-->
                                <div class="ratio ratio-3x4">
                                    <div class="d-flex align-items-center justify-content-center"
                                    style="z-index:0;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>


                                <!--image-->
                                <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="z-index:1;">
                                    <ratio-image-component
                                    url="{{ $user_prize->prize->image_path }}" style_class="ratio ratio-3x4 rounded-3"
                                    ></ratio-image-component>
                                </div>
                            </div>

                            <!--ポイント表示-->
                            <div class="bg-white text-center mt-1 px-1 rounded-pill position-relative">
                                <number-comma-component number="{{$user_prize->point}}"></number-comma-component>pt


                                @if($user_prize->point_history_id)
                                    <!--ポイント交換済み-->
                                    <div class="position-absolute top-50 start-0 translate-middle-y ps-1">
                                        <span class="text-warning">●</span>
                                    </div>
                                @endif

                                @if($user_prize->shipped_id)
                                    <!--ポイント交換済み-->
                                    <div class="position-absolute top-50 start-0 translate-middle-y ps-1">
                                        <span class="text-primary">●</span>
                                    </div>
                                @endif

                            </div>


                        </div>
                    </div>
                </li>
                @empty
                    <li class="text-center text-secondary border-0 py-5">
                        *取得商品はありません
                    </li>
                @endforelse

            </ul>


            <!--操作ボタン-->
            <div class="row mb-3 align-items-center"
            data-aos="fade-in"
            >
                <div class="col">
                    @if($prev_gacha_history)
                        <a href="{{route('gacha.result_history',$prev_gacha_history->key)}}"
                        class="btn w-100 py-0 fs-1 text-dark"
                        ><i class="bi bi-arrow-left-circle"></i></a>
                    @endif
                </div>
                <div class="col">
                    <div class="text-center" style="font-size:10px;">
                        <div class="">
                            <span class="text-warning">●</span>
                            <span>ポイント交換済み</span>
                        </div>
                        <div class="">
                            <span class="text-primary">●</span>
                            <span>発送申請済み</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    @if($next_gacha_history)
                        <a href="{{route('gacha.result_history',$next_gacha_history->key)}}"
                        class="btn w-100 py-0 fs-1 text-dark"
                        ><i class="bi bi-arrow-right-circle"></i></a>
                    @endif
                </div>
            </div>




            <!-- ガチャ情報 -->
            <div class="pt- my-5">


                <h5 class="fw-bold text-center mb-">ガチャ情報</h5>

                <a href="{{ $gacha->route }}"
                class="card border-secondary border-0 shadow bg-white
                text-dark text-center overflow-hidden text-decoration-none
                hover_anime" style="border-radius:1rem;">


                    <!--image-->
                    @include('gacha.common.top_image')

                    <!--metter-->
                    @include('gacha.common.metter')

                </a>

                <!--play_buttons-->
                @include('gacha.common.play_buttons')

            </div>


            @if( env('SHARE_BTNS') )
                <div class="py- my-5">
                    <h5 class="text-center fs-5 fw-bold mb-3">ガチャ結果を送る</h5>
                    @php
                    $sns_url  = route('gacha.result_history',$user_gacha_history->key);
                    $sns_text = $page_title
                    @endphp
                    @include('includes.sns_btn')
                </div>
            @endif


            <!-- その他のガチャ情報 -->
            <div class="pt- my-5">

                @include('gacha.common.result_gachas')

            </div>
        </div>
    </section>



@endsection
