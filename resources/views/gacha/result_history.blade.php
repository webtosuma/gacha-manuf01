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
            <u-gacha-result-form
            token="{{ csrf_token() }}"
            r_api_use_gacha_history_show="{{ route('api.use_gacha_history.show',$user_gacha_history) }}"
            r_gacha_category="{{ route('gacha_category',$gacha->category->code_name) }}"
            show_change_btn="0"
            no_exchange_point="{{ config('app.no_exchange_point')?1:0 }}"
            ></u-gacha-result-form>




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
                    @if($next_gacha_history)
                        <a href="{{route('gacha.result_history',$next_gacha_history->key)}}"
                        class="btn w-100 py-0 fs-1 text-dark"
                        ><i class="bi bi-arrow-right-circle"></i></a>
                    @endif
                </div>
            </div>



        </div>
    </section>
    <section class="mb-5">
        <div class="container px-3 py-4"  style="max-width:500px;">


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
