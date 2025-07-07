@extends('store.layouts.app')

<!----- title ----->
@section('title','商品一覧')


@section('style')
<style>
    .ratio-3x4{ --bs-aspect-ratio: 133.3%; }
</style>
@endsection


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store') }}">商品ストアー</a></li>
            <li class="breadcrumb-item active" aria-current="page">商品一覧</li>
            </ol>
        </nav>
    </div>


    <div class="container py-md-4 py-5 mb-5">
        <div class="row g-5 mx-0">
            <div class="col">


                <u-store-item-list
                token="{{ csrf_token() }}"
                r_api_list="{{ route('store_item.api') }}"
                category_id="{{ $search_inputs['category_id'] }}"
                keyword    ="{{ $search_inputs['keyword'] }}"
                order      ="{{ $search_inputs['order'] }}"
                ></u-store-item-list>


            </div>
            <div class="col-12 col-lg-3">

                <form action="{{route('store.search')}}" method="get"
                class="position-stickyyy ps-2" style="top: 5rem; ">


                    <h5>商品検索</h5>

                    <!--カテゴリー検索-->
                    <div class="mb-3">

                        <h5 class="fs-6 ">カテゴリー</h5>

                        @php
                        $categories = \App\Models\GachaCategory::where('is_published',1)
                        ->orderBy('created_at')->get();
                        @endphp
                        <select class="form-select" name="category_code_name">
                            <option value="">すべて</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->code_name}}"
                                @if (
                                    isset( $search_inputs['category_code_name'] ) && $search_inputs['category_code_name']==$category->code_name
                                ) selected @endif
                                >{{ $category->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <!--並び替え-->
                    <div class="mb-3">

                        <h5 class="fs-6 ">並び替え</h5>

                        @php $orders = \App\Models\StoreItem::orders(); @endphp
                        <select class="form-select" name="order">
                            @foreach ($orders as $order)
                                <option value="{{$order['key']}}"
                                @if (
                                    isset( $search_inputs['order'] ) && $search_inputs['order']==$order['key']
                                ) selected @endif
                                >{{ $order['label'] }}</option>
                            @endforeach
                        </select>
                    </div>


                    <!--キーワード検索-->
                    <div class="mb-5">
                        <h5 class="fs-6 ">キーワード検索</h5>
                        <u-store-item-search-keyword
                        token="{{ csrf_token() }}"
                        r_api_list="{{ route('store_item.api.search_history') }}"
                        keyword="{{ isset( $search_inputs['keyword'] ) ? $search_inputs['keyword'] : '' }}"
                        col="col-12"
                        ></u-store-item-search-keyword>
                    </div>


                    <button class="btn btn-primary rounded-pill w-100 my-4">検索</button>

                </form>
            </div>
        </div>

    </div>


@endsection
