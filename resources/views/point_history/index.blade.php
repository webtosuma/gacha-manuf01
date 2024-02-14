@extends('layouts.app')

<!----- title ----->
@section('title','ポイント履歴')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">ポイント履歴</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3>ポイント履歴</h3>
    <ul class="list-group list-group-flush">

        {{-- 所持ポイント --}}
        <li class="list-group-item bg-white py-4 fs-">
            <div class="d-flex justify-content-between align-items-center p-3 bg-white">
                <div class="col">
                    <div class="">所持ポイント：</div>
                    <div class="">
                        <span class="fs-3 fw-bold">
                            <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                        </span>
                        <span>pt</span>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('point_sail') }}" class="btn btn-warning text-white rounded-pill shadow">ポイント購入</a>
                </div>
            </div>
        </li>


        @include('point_history._types')


    </ul>
</div>
@endsection
