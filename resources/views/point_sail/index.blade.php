@extends('layouts.app')

<!----- title ----->
@section('title','ポイント購入')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">ポイント購入</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3>ポイント購入</h3>
    <ul class="list-group list-group-flush">
        <li class="list-group-item bg-white py-4 fs-"
        >購入するポイントを選択してください</li>


        @foreach ($point_sails as $point_sail)
            <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">

                <div class="">
                    <div class="d-flex align-items-center gap-2">
                        @include('includes.point_icon')
                        <h3 class="m-0 fw-bold">{{ $point_sail->value }}</h3>
                        <span>ポイント</span>
                    </div>

                    @if( $point_sail->service )
                    <div class="badge bg-danger-subtle rounded-pill fw-bold px-3">
                        {{-- <span class="text-dark fw-bold fs-5">{{ $point_sail->value - $point_sail->service }}</span> --}}
                        <span class="text-danger fw-bold fs-6">{{ '+'.$point_sail->service }}</span>
                        <span class="text-danger fw-bold">ポイントお得！</span>
                    </div>
                    @endif
                </div>

                <!--購入ボタン-->
                <a href="{{ route('point_sail.payment', $point_sail) }}"
                class="btn btn-lg btn-warning rounded-pill shadow py-1" style="width:8rem;">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <span>¥</span>
                        <h5 class="m-0 fw-bold">{{ $point_sail->price }}</h5>
                    </div>
                </a>
            </div></li>
        @endforeach


        <li class="list-group-item bg-white py-1 form-text text-end"
        >*価格は全て税込み価格です。</li>
    </ul>
</div>
@endsection
