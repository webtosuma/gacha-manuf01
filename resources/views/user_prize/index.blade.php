{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','取得した商品')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">取得した商品</li>
            </ol>
        </nav>
    </div>





    <div class="container py-md-4 pb-5 mb-5">
        <h3 class="d-none d-md-block">取得した商品</h3>

        <u-user-prize-form
        token="{{ csrf_token() }}"
        no_exchange_point="{{ config('app.no_exchange_point') ?1:0 }}"
        r_api_user_prize ="{{ route('api_user_prize') }}"
        r_shipped_appli  ="{{ route('shipped.appli') }}"

        r_api_exchange_points="{{ route('api.user_prize.exchange_points') }}"
        r_redirect="{{route('user_prize.exchange_points')}}"
        ></u-user-prize-form>

        {{-- r_exchange_points="{{ route('user_prize.exchange_points') }}" --}}

    </div>
@endsection
