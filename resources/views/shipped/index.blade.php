{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','発送')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送</li>
            </ol>
        </nav>
    </div>




    <div class="container py-md-4 mb-5">
        <h3 class="d-none d-md-block mb-5">発送</h3>

        <u-shipped-list
        token="{{ csrf_token() }}"
        mount_state_id="{{ $state_id }}"
        r_api_list="{{ route('shipped.api') }}"
        ></u-shipped-list>

    </div>
@endsection
