{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','取得した商品')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">取得した商品</li>
            </ol>
        </nav>
    </div>





    <div class="container py-md-4 pb-5 mb-5">
        <h3 class="d-none d-md-block">取得した商品</h3>

        @php
        $shipped_point     = (Int) config('gacha.shipped_point',0);
        $limit_prize_point = (Int) config('gacha.shipped.limit_prize_point',0);
        @endphp
        @if( $shipped_point>0 || $limit_prize_point>0 )
            <div class="alert alert-primary">
                <div class="fw-bold"><i class="bi bi-exclamation-triangle"></i>ご確認ください</div>

                @if( $shipped_point>0 )
                    <div>発送申請には、合計{{ number_format($shipped_point) }}pt以上の消費が必要です。</div>
                @endif


                @if( $limit_prize_point>0 )
                    <div>発送申請には、合計{{ number_format($limit_prize_point) }}pt以上の商品選択が必要です。</div>
                @endif
            </div>
        @endif



        <u-user-prize-form
        token="{{ csrf_token() }}"
        no_exchange_point="{{ config('app.no_exchange_point') ?1:0 }}"
        change_ticket ="{{ config('u_rank_ticket.change_prize_to_ticket')?1:0 }}"

        r_api_user_prize ="{{ route('api_user_prize') }}"
        r_shipped_appli  ="{{ route('shipped.appli') }}"

        r_api_exchange_points      ="{{ route('api.user_prize.exchange_points') }}"
        r_redirect_exchange_points ="{{ route('user_prize.exchange_points')}}"

        r_api_exchange_tickets     ="{{ route('api.user_prize.exchange_tickets') }}"
        r_redirect_exchange_tickets="{{ route('user_prize.exchange_tickets')}}"
        ></u-user-prize-form>

        {{-- r_exchange_points="{{ route('user_prize.exchange_points') }}" --}}

    </div>
@endsection
