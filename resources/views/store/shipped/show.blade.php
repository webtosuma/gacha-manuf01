@extends('store.layouts.sub')

<!----- title ----->
@section('title','発送詳細')


@section('meta')
    @php $header_back_btn = true; @endphp
@endsection


@section('style')
<link href="{{ asset('css/steps.css') }}" rel="stylesheet">
@endsection


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.shipped') }}">発送履歴</a></li>
            <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>
    </div>


<div class="container py-md-4 mb-5">
    <div class="mx-auto" style="max-width:900px;">


        <h3 class="d-none d-md-block mb-5">発送詳細</h3>


        <!-- ステップ -->
        @include('store.shipped.show_steps')

        <section class="card card-body bg-white my-4 text-center">

            @if($store_history->state_id>20)
                <!--発送完了-->
                <h3 class="text-success">商品は発送済みです</h3>
            @else
                <!--未完了-->
                <h3 class="text-warning">発送準備中</h3>
                <p>準備が整い次第、商品を発送いたします。</p>
            @endif

        </section>

        <!-- 本文 -->
        @include('store.shipped.show_body')


        <section class="my-5">
            <div class="col-md-8 mx-auto my-3">
                <a href="{{route('store.shipped')}}"
                class="btn btn-lg btn-light border rounded-pill w-100"
                >発送履歴に戻る</a>
            </div>
        </section>


        <!--注意事項-->
        @include('store.includes.notes')



    </div>
</div>
@endsection
