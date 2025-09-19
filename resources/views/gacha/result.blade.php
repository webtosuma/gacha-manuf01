@extends('layouts.app')

<!--title-->
@section('title','「'.$gacha->name.'」の結果')


@section('meta')
    @php
        $meta_title = '「'.$gacha->name.'」の結果';
        $meta_image = $bg_image;
    @endphp
@endsection


@section('style')
    <style>
        main{ padding-top: 0rem; }
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
        /* body{
            background-image: url({{ $bg_image }});
        } */


        /* アイコンを下にスライドさせるアニメーション */
        .slide-updown {
        display: inline-block;
        animation: slideDown 2s ease-in-out infinite alternate;
        }

        @keyframes slideDown {
        from { transform: translateY(-16px); }
        to   { transform: translateY(0px); } /* 8px下に移動（調整可） */
        }

        /* 視覚負荷軽減対応 */
        @media (prefers-reduced-motion: reduce){
        .slide-updown { animation: none; }
        }

    </style>
@endsection



@section('content')
    <section id="result" style="padding-top:3rem; min-height: 80vh;">
        <div class="container px-3 py-4"  style="max-width:500px;">


            <h2 class="p- mb- fs-6">
                <div class="rounded-3 p-3 text-light position-relative" style="background: rgb(0, 0, 0, .7);">

                    <div class="mb-2" style="font-size:10px;">
                        <div class="">{{$user_gacha_history->created_at->format('Y/m/d H:i')}}</div>
                    </div>


                    <div class="mb-3" style="font-size:.8rem;">
                        <div class="fs-5 text-center">{{$page_title}}</div>
                    </div>

                </div>

                <!--下へスクロールボタン-->
                <div class="d-flex justify-content-end">
                    <a href="#all_check" style="z-index:4;"
                    class="btn btn-dark rounded-pill py-0
                    d-flex align-items-center gap-2
                    slide-updown">
                    <i class="bi bi-arrow-down-circle-fill fs-3"></i>
                    <span style="font-size:11px;">「全て選択」へスクロール</span>
                    </a>
                </div>

            </h2>


            <!--商品発送フォーム-->
            <form action="{{ route( 'shipped.appli') }}" method="POST">
                @csrf

                <!--カード一覧-->
                <u-gacha-result-form
                token="{{ csrf_token() }}"
                r_api_use_gacha_history_show="{{ route('api.use_gacha_history.show',$user_gacha_history) }}"
                r_gacha_category="{{ route('gacha_category',$gacha->category->code_name) }}"
                r_redirect="{{route('user_prize.exchange_points')}}"
                r_user_prize    ="{{route('user_prize')}}"

                no_exchange_point="{{ config('app.no_exchange_point') ?1:0 }}"
                change_ticket    ="{{ config('u_rank_ticket.change_prize_to_ticket')?1:0 }}"

                r_api_exchange_points      ="{{ route('api.user_prize.exchange_points') }}"
                r_redirect_exchange_points ="{{ route('user_prize.exchange_points')}}"
                r_api_exchange_tickets     ="{{ route('api.user_prize.exchange_tickets') }}"
                r_redirect_exchange_tickets="{{ route('user_prize.exchange_tickets')}}"
                ></u-gacha-result-form>


            </form>



        </div>
    </section>
    <section class="mb-5">
        @if( env('SHARE_BTNS') )
            <div class="mb-5 py-">
                <h5 class="text-center fs-5 fw-bold mb-3">ガチャ結果を送る</h5>
                @php
                $sns_url  = route('gacha.result_history',$user_gacha_history->key);
                $sns_text = $page_title
                @endphp
                @include('includes.sns_btn')
            </div>
        @endif
        <!-- その他のガチャ情報 -->
        <div class="container px- pb-5"  style="max-width:500px;">

            @include('gacha.common.result_gachas')

        </div>

    </section>


    <!--ボトムメニュー-->
    <div class="position-fixed bottom-0 end-0 w-100 py-3 text-white"
    style="z-index:50; background:rgb(0, 0, 0, .7);">
        <div class="container mx-auto" style="max-width:600px;">
            <div class="card-body">


                <h5 class="text-center text-white m-0">もう一度ガチャる？</h5>


            </div>

            <!--play_buttons-->
            @include('gacha.common.play_buttons')


        </div>
    </div>


@endsection
