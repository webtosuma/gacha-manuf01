@extends('layouts.app')
{{-- @extends('layouts.sub') --}}

<!----- title ----->
@section('title','ポイント購入')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">ポイント購入</li>
            </ol>
        </nav>
    </div>


    <div class="container py-4 mb-5">
        <h3 class="d-none d-md-block">ポイント購入</h3>
        <ul class="list-group list-group-flush">
            <li class="list-group-item bg-white py-4 fs-">
                購入するポイントを選択してください

                {{-- <div class="mt-2" >
                    ご利用可能な決済方法
                    <img src="{{asset('storage/site/image/stripe_card.png')}}" alt="ご利用可能な決済方法" style="height:3rem;">
                </div> --}}



                {{-- <h3 class="text-danger">只今、ポイント購入を停止しています。</h3>
                <p class="border border-warning border-3 p-3">
                    現在、お客様がご購入いただいたポイント購入のに関するエラーが発生しており、重複し
                    て処理されている状況が確認されました。<br>
                    お客様にはご迷惑をおかけしておりますことを深くお詫び申し上げます。<br>
                    なお、このエラーにより発生したポイント購入の重複処理は、クレジットの引き落としに
                    は影響を及ぼしておりません。<br>
                    お客様のクレジットカードに重複請求が行われることはございませんので、ご安心くださ
                    い。<br>
                    当サイトでは現在、一度の決済サービスを停止しており、早急にエラーの修正作業を進
                    め、サービスの正常化を図るため、全力で修正対応を行っております。<br>
                    お客様には、これに伴い一時的にポイント購入が制限され、また復旧までしばらくお時間
                    をいただくかもしれませんが、お客様のご理解とご協力を賜りますようお願い申し上げま
                    す。
                </p> --}}
            </li>


            @foreach ($point_sails as $point_sail)
                <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">

                    <div class="">
                        <div class="d-flex align-items-center gap-2">
                            @include('includes.point_icon')
                            <h3 class="m-0 fw-bold">
                                <number-comma-component number="{{ $point_sail->value }}"></number-comma-component>
                            </h3>
                            <span>pt</span>
                        </div>

                        @if( $point_sail->service )
                        <div class="badge bg-danger-subtle rounded-pill fw-bold px-3">
                            <span class="text-danger fw-bold fs-6">{{ '+'.$point_sail->service }}</span>
                            <span class="text-danger fw-bold">ポイントお得！</span>
                        </div>
                        @endif
                    </div>

                    <!--購入ボタン-->
                    <a href="{{ route('point_sail.payment', $point_sail) }}"
                    class="btn btn-lg btn-warning text-white rounded-pill shadow py-1 " style="width:8rem;">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <span>¥</span>
                            <h5 class="m-0 fw-bold">
                                <number-comma-component number="{{ $point_sail->price }}"></number-comma-component>
                            </h5>
                        </div>
                    </a>
                </div></li>
            @endforeach


            <li class="list-group-item bg-white py-1 form-text text-end"
            >*価格は全て税込み価格です。</li>
        </ul>


        <div class="mt-5">
            <h6>ご利用可能な決済方法</h6>
            <div class="row g-2">
                <div class="col">
                    <div class="card card-body px- bg-body">
                        <div class="">クレジットカード</div>
                        <i class="bi bi-credit-card-fill fs-4"></i>
                        <div class="">
                            <img src="{{asset('storage/site/image/stripe_card.png')}}" alt="ご利用可能な決済方法" style="height:2rem;">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="card card-body px- bg-body h-100">
                        <div class="">銀行振込</div>

                        <div class=""><i class="bi bi-bank2 fs-4"></i></div>
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="card card-body px- bg-body h-100">
                        <div class="">Apple Pay</div>

                        <div class=""><i class="bi bi-apple fs-4"></i></div>
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="card card-body px- bg-body h-100">
                        <div class="">Google Pay</div>

                        <div class=""><i class="bi bi-google fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="my-5 p-3">
        <div class="col-md-4 mx-auto my-3">
            <a href="#" onClick="history.back(); return false;"
            class="btn btn-lg btn-light border rounded-pill w-100"
            >戻る</a>
        </div>
    </div>
@endsection
