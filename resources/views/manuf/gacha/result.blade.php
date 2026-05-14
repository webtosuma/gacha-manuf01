@extends('manuf.layouts.app')

<!--title-->
@section('title',$page_title)


@section('meta')
    @php
        $meta_title = $page_title;
        $meta_image = $bg_image;
    @endphp
@endsection


@section('style')
    <style>
        #bgWindow{
            background-image: url({{ $bg_image }});
        }

        .result-text{
            background: linear-gradient(
                90deg,
                var(--bs-primary),
                var(--bs-success)
            );

            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;

            font-weight:900;
            letter-spacing:.08em;
        }

        .gradient-bg{
            position: relative;
            border-radius: 1rem;
            padding: 5px; /* ボーダー幅 */

            background: linear-gradient(
                315deg,
                var(--bs-primary),
                var(--bs-success)
            );
        }

        /* 中身 */
        .gradient-bg-inner{
            background: #fff;
            border-radius: calc(1rem - 5px);
            padding: 1rem;
        }

    </style>
@endsection



@section('content')
    <section id="result" style="padding-top:3rem; min-height: 80vh;">
        <div class="container px-3 py-4"  style="max-width:600px;">


            <h2 class="py-3 mb-3">

                <div class="result-text fw-bold text-center fs-1 mb-3 ">
                    <i class="bi bi-stars"></i>
            
                    <span class="">
                        抽選結果
                    </span>
                
                    <i class="bi bi-stars"></i>    
                </div>
            
                <div class="fs-6 text-center">
                    おめでとうございます！<br>
                    以下の商品を取得しました！
                </div>
            </h2>
            
            

            
            <!--カード一覧-->
            <div class="gradient-bgxx shadowxx ">
                <div class="gradient-bg-innerxx p-3">
                    <u-gacha-result-form
                    show_change_btn="0"
        
                    token="{{ csrf_token() }}"
                    r_api_use_gacha_history_show="{{ route('api.use_gacha_history.show', $gacha_history) }}"
                    r_gacha_category="{{ route('gacha_category',$gacha->category_code_name) }}"
                    r_user_prize    ="{{route('user_prize')}}"
        
                    no_exchange_point="1"
                    change_ticket    ="0"
        
                    ></u-gacha-result-form>    
                </div>
            </div>


            <div class="row g-3 mt-3 mb-5">
                <div class="col-12 col-md">
                    <a href="" 
                    class="btn btn-light border-info rounded-pill w-100"
                    ><div class="d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-file-ruled fs-4"></i>
                        <span>タイトル詳細に戻る</span>
                    </div></a>
                </div>
                
                <div class="col-12 col-md">
                    <a href="" 
                    class="btn btn-info text-white rounded-pill w-100"
                    ><div class="d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-seam fs-4"></i>
                        <span>発送一覧を見る</span>
                    </div></a>
                </div>
            </div>


            <div class="card card-body border mb-4 ">
                <div class="row">
                    <div class="col-12 text-info">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-truck fs-2"></i>
                            <span class="fw-bold">商品の配送について</span>
                        </div>
                    </div>
                    <div class="col-12">
                        所得した商品は、購入時にご指定いただいた住所へお送りいたします。<br>
                        発送状況は、マイメニューの発送一覧よりご確認ください。
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!--ボトムメニュー-->
    {{-- <div class="position-fixed bottom-0 end-0 w-100 py-3 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container mx-auto" style="max-width:600px;">
            <div class="card-body">


                <h5 class="text-center text-white m-0">もう一度ガチャる？</h5>


            </div>

            <!--metter-->
            @php $metter_bg_color = ''; @endphp
            @include('gacha.common.metter')

            <!--play_buttons-->
            @include('gacha.common.play_buttons')


        </div>
    </div> --}}



@endsection
