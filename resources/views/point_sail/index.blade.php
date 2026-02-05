@extends('layouts.app')
{{-- @extends('layouts.sub') --}}

<!----- title ----->
@section('title','ポイント購入')


@section('style')
    @php
        /* 背景パス */
        $bg_image = \App\Http\Controllers\AdminBackGroundController::getBgSub();
    @endphp
    <style>
        #bgWindow{
            background-image: url({{ $bg_image }});
            opacity: .5;
        }
    </style>
@endsection


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">ポイント購入</li>
            </ol>
        </nav>
    </div>


    <div class="container py-md-4 mb-5">
        <h3 class="d-none d-md-block">
            ポイント購入
            @if($payment_type){{'（'.$payment_type.'）'}}@endif
        </h3>

        {{-- <p class="border border-danger bg-danger-subtle border-3 p-3">
            現在、決済システムにエラーが発生しており、ポイントをご購入いただくことができません。<br>
            お客様にはご迷惑をおかけしておりますことを深くお詫び申し上げます。<br>
            当サイトでは現在、早急にエラーの修正作業を進め、サービスの正常化を図るため、全力で修正対応を行っております。<br>
            お客様には、これに伴い一時的にポイント購入が制限され、また復旧までしばらくお時間
            をいただくかもしれませんが、お客様のご理解とご協力を賜りますようお願い申し上げま
            す。
        </p> --}}



        <ul class="list-group list-group-flush">
            <li class="list-group-item bg-white py-4 fs-">

                <div class="">購入するポイントを選択してください</div>



                @if( Auth::check() && $rank_ratio > 1 )
                    @php $now_rank = Auth::user()->now_rank; @endphp
                    <div class="row g-2 mt-2 align-items-center">
                        <div class="col-auto me-2" style="width:6rem;">
                            <ratio-image-component
                            style_class="ratio ratio-16x9 overflow-hidden
                            position-relative shiny"
                            url="{{ $now_rank->image_path }}"
                            ></ratio-image-component>
                        </div>
                        <div class="col-auto">
                            <div class="form-text"><span class="fw-bold me-1">{{$now_rank->label}}ランク</span>ユーザー様は、</div>
                            <div class="col-12 col-md-auto fs- text-danger rounded-pill fw-bold">

                                <span>{{ ($rank_ratio-1)*100 }}</span><span class="fs-">%ポイント還元！</span>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="badge bg-success-subtle rounded-pill fw-bold px-3">
                        <span class="text-success fw-bold fs-6">{{ $point_sail->value*($rank_ratio-1) }}</span>
                        <span class="text-success fw-bold">pt 会員ランク還元！</span>
                    </div> --}}
                @endif
            </li>



            @foreach ($point_sails as $point_sail)
                <li class="list-group-item bg-white py-3">
                    {{-- 会員ランク還元 --}}
                    @php $reduction_point =  ( $point_sail->value * ($rank_ratio-1) );  /* 還元ポイント */ @endphp
                    @if( $rank_ratio > 1  &&  $reduction_point >=1 )
                        <div class="d-flex align-items-center gap-2 flex-wrap">

                            <div class="badge border border-dangerr rounded-pill text-danger fw-bold px-">
                                <span class="text-dark fs-6">{{ number_format($point_sail->value) }}</span>
                                <span class="text-dark">pt</span>
                                <i class="bi bi-plus-lg"></i>
                                <span class=" fs-6">{{ number_format($reduction_point) }}</span>
                                <span class="">pt 還元！</span>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between">

                        <div class="d-flex align-items-center justify-content-start gap-2">

                            <!--P icon-->
                            @include('includes.point_icon')

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="m-0 fw-bold fs-">
                                    <number-comma-component number="{{ $point_sail->value + $reduction_point }}"></number-comma-component>
                                </h3>
                                <span>pt</span>
                            </div>
                        </div>

                        @if( env('STRIPE_KEY') )

                            <!--購入ボタン(stripe)-->
                            <a href="{{ $point_sail->r_payment }}"
                            class="btn btn-lg btn-warning text-white rounded-pill shadow   hover_anime  py-1 " style="width:8rem;">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <span>¥</span>
                                    <h5 class="m-0 fw-bold">
                                        <number-comma-component number="{{ $point_sail->price }}"></number-comma-component>
                                    </h5>
                                </div>
                            </a>


                        @elseif( env('FINCODE_KEY') )

                            <!-- 購入ボタン(fincode) -->
                            <button type="button"
                            class="btn btn-lg btn-warning text-white rounded-pill shadow   hover_anime  py-1 "
                            style="width:8rem;"
                            data-bs-toggle="modal"
                            data-bs-target="#pointModal{{$point_sail->id}}">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <span>¥</span>
                                    <h5 class="m-0 fw-bold">
                                        <number-comma-component number="{{ $point_sail->price }}"></number-comma-component>
                                    </h5>
                                </div>
                            </button>

                        @endif

                    </div>


                    <!-- Modal -->
                    @include('point_sail._modal')


                    <div class="d-flex flex-colum flex-wrap gap-1 mt-1" style="font-size:11px;">

                        {{-- 会員ランク還元 --}}
                        {{-- @if( $rank_ratio > 1 )
                        <div class="badge border border-danger rounded-pill fw-bold px-3">
                            <span class="text-danger fw-bold fs-">{{ $point_sail->value*($rank_ratio-1) }}</span>
                            <span class="text-danger fw-bold">pt 還元！</span>
                        </div>
                        @endif --}}


                        {{-- お得 --}}
                        @if( $point_sail->service )
                        <div class="badge border border-warning rounded-pill text-warning fw-bold px-">
                            <span class=" fs-6">{{ number_format($point_sail->value*$rank_ratio - $point_sail->price) }}</span>
                            <span class="">pt</span>
                            <span class=" fs-6">お得！</span>
                        </div>
                        @endif

                    </div>
                    {{-- チケット還元 --}}
                    @if( !env('NEW_TICKET_SISTEM_NOTICKET') && $point_sail->ticket > 0 )
                    <div class="">
                        <div class="d-flex align-items-center gap-2">
                            <div class="col-auto">
                                <img src="{{asset('storage/site/image/ticket/success.png')}}"
                                alt="チケット" class="d-block mx-auto"  style=" width:1.4rem; height:1.4rem;">
                                {{-- <span class="text-success fw-bold" style="font-size:8px;">チケット</span> --}}
                            </div>
                            <div class="col">
                                <span class="fs-6">×</span>
                                <span class="fs-5 fw-bold">
                                    <number-comma-component number="{{ $point_sail->ticket }}"></number-comma-component>
                                </span>
                                <span>枚</span>
                                <span class="form-text text- fw-bold">チケットプレゼント！</span>
                            </div>
                        </div>
                    </div>
                    @endif

                </li>
            @endforeach


            <li class="list-group-item bg-white py-1">

                <div class="form-text text-end mb-3">*価格は全て税込み価格です。</div>

                <div class="col-md-8 mx-auto mb-3">
                    <a class="btn border rounded-pill w-100 "
                    href="{{ route('tradelaw') }}">特定商取引法に基づく表記</a>
                </div>

            </li>


        </ul>

        <div class="mt-5">
            <h6 class="fs-5">ご利用可能な決済方法</h6>

            <div class="row g-3">
                @php
                $subscriptions_count = \App\Models\PointSail::where('is_subscription',true)//サブスクのみ
                ->where('is_published',true)//公開中のみ
                ->count();
                @endphp
                @if ( env('SUBSCRIPTION',false) && $subscriptions_count>0 )
                    <!--サブスク-->
                    <div class="col-12 ">
                        <a href="{{route('point_sail.subscription')}}"
                        class="btn btn-lg btn-dark p-3 w-100 h-100 shadow position-relative">
                            <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                            <div class="fw-bold">サブスクプランはこちら</div>
                        </a>
                    </div>
                @endif

                <div class="col-12 col-md-4">
                    <a href="{{ route( 'point_sail',['payment_type'=>'クレジットカード'] ) }}"
                    class="btn btn-light hover_anime shadow p-3 text-start w-100 h-100 position-relative">
                        <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                        <div class="">クレジットカード</div>
                        <i class="bi bi-credit-card-fill fs-4"></i>
                        <div class="">
                            @if (config('stripe.payment_method_types.jcb'))
                                <img src="{{asset('storage/site/image/credit/01.png')}}" alt="ご利用可能な決済方法" style="height:2rem;">
                            @else
                                <img src="{{asset('storage/site/image/credit/02.png')}}" alt="ご利用可能な決済方法" style="height:2rem;">
                            @endif
                        </div>
                    </a>
                </div>
                {{-- <div class="col-12 col-md-4">
                    <a href="{{ route( 'point_sail', ['payment_type'=>'クレジットカード(JCB)'] ) }}"
                    class="btn btn-light hover_anime shadow p-3 text-start w-100 h-100 position-relative">
                        <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                        <div class="">クレジットカード(JCB)</div>
                        <i class="bi bi-credit-card-fill fs-4"></i>
                        <div class="">JCB</div>
                    </a>
                </div> --}}
                @if (config('stripe.payment_method_types.applepay'))
                    <div class="col-12 col-md-4">
                        <a href="{{ route( 'point_sail', ['payment_type'=>'Apple Pay'] ) }}"
                        class="btn btn-light hover_anime shadow p-3 text-start w-100 h-100 position-relative">
                            <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                            <div class="">Apple Pay</div>
                            <div class=""><i class="bi bi-apple fs-4"></i></div>
                        </a>
                    </div>
                @endif
                @if (config('stripe.payment_method_types.googlepay'))
                    <div class="col-12 col-md-4">
                        <a href="{{ route( 'point_sail', ['payment_type'=>'Google Pay'] ) }}"
                        class="btn btn-light hover_anime shadow p-3 text-start w-100 h-100 position-relative">
                            <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                            <div class="">Google Pay</div>
                            <div class=""><i class="bi bi-google fs-4"></i></div>
                        </a>
                    </div>
                @endif
                @if (config('stripe.payment_method_types.paypay'))
                    <div class="col-12 col-md-4">
                        <a href="{{ route( 'point_sail', ['payment_type'=>'PayPay'] ) }}"
                        class="btn btn-light hover_anime shadow p-3 text-start w-100 h-100 position-relative">
                            <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                            <div class="">PayPay</div>
                            <div class=""><i class="bi bi-phone fs-4"></i></div>
                        </a>
                    </div>
                @endif
                @if (config('stripe.payment_method_types.konbini'))
                    <div class="col-12 col-md-4">
                        <a href="{{ route( 'point_sail', ['payment_type'=>'コンビニ支払い'] ) }}"
                        class="btn btn-light hover_anime shadow p-3 text-start w-100 h-100 position-relative">
                            <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                            <div class="">コンビニ支払い</div>

                            <div class=""><i class="bi bi-shop fs-4"></i></div>
                        </a>
                    </div>
                @endif
                @if (config('stripe.payment_method_types.customer_balance'))
                    <div class="col-12 col-md-4">
                        <a href="{{ route( 'point_sail', ['payment_type'=>'銀行振込'] ) }}"
                        class="btn btn-light hover_anime shadow p-3 text-start w-100 h-100 position-relative">
                            <div class="position-absolute top-50 end-0 translate-middle-y p-3"><i class="bi bi-chevron-right fs-4"></i></div>
                            <div class="">銀行振込</div>
                            <div class=""><i class="bi bi-bank2 fs-4"></i></div>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <p class="d-block mt-5">
            <h6 class="fw-bold ">クレジットカード決済のセキュリティ対策について</h6>
            当サービスでは、お客様の安全な取引のために <strong class="text-warning">3Dセキュア2.0認証</strong> を導入しております。<br>
            3Dセキュア認証が設定されていないカードをご利用の場合、<strong class="text-warning"> 決済が完了しない</strong>ことがございます。<br>
            お手持ちのクレジットカードの3Dセキュア設定が有効であることをご確認ください。<br>
            <br>
            <h6 class="fw-bold ">3Dセキュア認証が設定されていない場合</h6>
            詳細についてはお使いのカード会社のサポート窓口にお問い合わせいただき、設定を行ってから再度ご利用いただけますようお願い申し上げます。
        </p>


    </div>







    <div class="my-5 p-3">
        <div class="col-md-4 mx-auto my-3">
            <a href="#" onClick="history.back(); return false;"
            class="btn btn-lg btn-light border rounded-pill w-100"
            >戻る</a>
        </div>
    </div>
@endsection
