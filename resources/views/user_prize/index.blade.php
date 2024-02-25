{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','取得した商品')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">取得した商品</li>
            </ol>
        </nav>
    </div>





    <div class="container py-4 pb-5 mb-5">
        <h3 class="d-none d-md-block">取得した商品</h3>

        <u-user-prize-form
        token="{{ csrf_token() }}"
        r_api_user_prize ="{{ route('api_user_prize') }}"
        r_exchange_points="{{ route('user_prize.exchange_points') }}"
        r_shipped_appli  ="{{ route('shipped.appli') }}"
        ></u-user-prize-form>

    </div>
@endsection
