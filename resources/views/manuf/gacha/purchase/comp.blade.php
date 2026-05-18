@extends('manuf.layouts.app')

<!--title-->
@section('title','購入完了')


<!--meta-->
@section('meta')
    @php
    $meta_title = '購入完了';
    $meta_image = $gacha_title->image_samune_path;
    @endphp
@endsection


@section('style')
<style>
    /* ホバー時 */
    .result-btn:hover{
        opacity:0.8;
        transform:scale(0.95);
    }

    .bg-rainbow { 
    /* 200%の幅を持つグラデーション背景 */
    background: linear-gradient(
        to right, 
        #ff2e63, #ff7b00, #ffc300, #00c853, #00a6ff, #3d5afe, #8e24aa, #ff2e63 
    ) 0% 0% / 200% 100%; 
    
    /* アニメーションの適用 */
    animation: rainbow-flow 8s linear infinite; 
    }

    /* 左から右に流れて見えるようにするキーフレーム */
    @keyframes rainbow-flow {
    0% {
        background-position: 0% 0%;
    }
    100% {
        background-position: -200% 0%; /* 背景を左に引くことで、右へ流れる動きを作る */
    }
    }


</style>
@endsection



@section('script')
    {{--- 紙吹雪　CDN ---}}
    @include('includes.confetti_js')
@endsection




@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manuf') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ $gacha_title->r_show }}">{{$gacha_title->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">購入完了</li>
            </ol>
        </nav>
    </div>



    <div class="containerXX px-3">
        <div class="row mx-0 g-4 g-md-3 justify-content-center">


            <!--flex-c2-1 -->
            <div class="col-12 col-lg" style="min-width:330px">
                <div class="mx-auto pb-5" style="max-width:768px;">
                    


    
                    <h2 class="text-center pt-5 ">
                        <div class="text-success mb-3" style="font-size:6rem;">
                            <i class="bi bi-check-circle-fill "></i>
                        </div>
    
                        <div class="fw-bold mb-3">購入が完了しました</div>

                        <p class="fs-5">
                            ご利用ありがとうございます。<br>
                            ガチャの結果を確認しましょう!
                        </p>
                    </h2>




                    <div class="">




    
                        @switch($history->status)

                            @case('paid')
                            <div class="px-3">
                                <img class="w-100"
                                src="{{asset('storage/site/image/munf_purchase/comp01.png')}}" 
                                alt="">    
                            </div>
                            <a href="{{route('gacha.movie',$history->items[0]->code)}}" 
                            class="btn btn-lg bg-rainbow hover_anime
                            fs-3 text-white fw-bold border-4 border-light py-2
                            w-100 shadow rounded-pill my-2">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fs-1 bi bi-stars"></i>
                                    <span class="mx-2 d-lg-none fs-5 fs-lg-3">結果を確認する</span>
                                    <span class="mx-2 d-none d-lg-inline-block">結果を確認する</span>
                                    <i class="fs-1 bi bi-chevron-double-right"></i>    
                                </div>
                            </a>
                            {{-- <img class="w-100"
                            src="{{asset('storage/site/image/munf_purchase/result_btn.png')}}" 
                            alt="結果を確認"> --}}

                            <div class="text-center text-secondary small"
                            >＊結果は、マイメニューの「ガチャ履歴」からも確認できます。</div>
                            @break
                        
                            @case('pending')
                            <a class="btn btn-lg btn-secondary 
                            disabled
                            w-100 shadow rounded-pill mb-2">
                                支払い未完了
                            </a>
                            <div class="text-center text-danger">
                                ＊支払いの処理が完了いたしましたら、結果をご確認ください。<br>
                                ＊結果は、マイメニューの「ガチャ履歴」からも確認できます。
                            </div>
                            @break

                        @endswitch
                    </div>

                </div>
            </div>




            <!--flex-c2-2 -->
            <aside class="col-auto">
                <div class="position-sticky" style="top: 2rem; ">


                    <!--購入内容-->
                    <section class="mb-4">
                        <h5 class="fw-bold">購入内容</h5>

                        @foreach ($history->items as $item)
                            @php
                                $machine = $item->machine;
                            @endphp

                            @include('manuf.gacha.purchase.common.title_card')

                        @endforeach

                    </section>


                    <!--会計明細-->
                    @include('manuf.gacha.purchase.common.account_details')


                    <section class="mt-5">
                        <a href="{{ $gacha_title->r_show }}" 
                        class="btn btn-light border w-100 rounded-pill"
                        ><div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-file-ruled fs-4"></i>
                            <span>タイトル詳細に戻る</span>
                        </div></a>
                    </section>

                </div>
            </aside>

            
        </div>
    </div>





@endsection
