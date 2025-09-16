{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','買取表')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">買取表</li>
            </ol>
        </nav>
    </div>





    <div class="container py-md-4 pb-5 mb-5">
        <h3 class="d-none d-md-block">買取表</h3>

        <!--カテゴリー-->
        @if($categories->count()>1)
            <div class="bg-">
                @include('purchase.category.index')
            </div>
        @endif

        <u-purchase-list
        token="{{ csrf_token() }}"
        r_api_list    ="{{ route('admin.api.purchase') }}"
        r_api_update  ="{{ route('admin.api.purchase.update') }}"
        r_create      ="{{ route('admin.purchase.create') }}"
        category_id   ="{{ $category_id }}"
        ></u-purchase-list>

    </div>
@endsection
