{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','ポイント履歴')


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">ポイント履歴</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block">ポイント履歴</h3>
    <ul class="list-group list-group-flush">

        {{-- 所持ポイント --}}
        <li class="list-group-item bg-white py-4 fs-">
            <div>所持ポイント：</div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="col-auto pe-2">

                    @include('includes.point_icon')

                </div>
                <div class="col">
                    <div class="">
                        <span class="fs-3 fw-bold">
                            <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                        </span>
                        <span>pt</span>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('point_sail') }}" class="btn btn- btn-warning text-dark rounded-pill shadow">ポイント購入</a>
                </div>
            </div>
            <div class="form-text text-secondary">{{Auth::user()->point_deadline_text}}</div>

        </li>


        @include('point_history._types')


    </ul>
    <div class="">
        <!-- ページネーション -->
        <div class="d-flex justify-content-start  mt-3">
            {{ $point_histories->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
        </div>
    </div>
</div>
@endsection
