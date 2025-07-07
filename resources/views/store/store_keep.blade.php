@extends('store.layouts.app')

<!----- title ----->
@section('title','買い物カート')


@section('content')



    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'買い物カート'}}</li>
            </ol>
        </nav>
    </div>

    <div class="container py-md-4 pb-5 mb-5">

        <form action="{{route('store.purchase.appli')}}" method="post">
            @csrf

            <u-store-keep-list
            token="{{ csrf_token() }}"
            r_api_list="{{ route('store.keep.api') }}"
            ></u-store-keep-list>


        </form>
    </div>

@endsection
