@extends('store.layouts.sub')

<!----- title ----->
@section('title','お知らせ')


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ</li>
            </ol>
        </nav>
    </div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block mb-5">お知らせ</h3>

    <div class="my-5">

        <u-infomation-list
        token="{{ csrf_token() }}"
        r_api_list="{{route('infomation.api.list')}}"
        no_types_string="gacha"
        ></u-infomation-list>

    </div>
</div>

@endsection
