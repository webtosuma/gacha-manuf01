@extends('store.layouts.sub')

<!----- title ----->
@section('title','購入した商品')


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">購入した商品</li>
            </ol>
        </nav>
    </div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block mb-5">購入した商品</h3>

    <u-store-purchased-list
    token="{{ csrf_token() }}"
    r_api_list="{{ route('store.api.purchased') }}"
    ></u-store-purchased-list>



</div>

@endsection
