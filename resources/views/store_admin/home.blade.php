@extends('store_admin.layouts.app')


@section('title','Admin TOP')


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ 'Top' }}</li>
            </ol>
        </nav>


        <section class="row g-0 mt-5">

            <div class="col-12"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.user')}}">
                <div class="fw-bold">登録ユーザー</div>
                <div class="fs-3">{{ number_format($users_count)}} </div>
            </a></div>

        </section>



        <section class="card card-body border-0 rounded-4 mb-4">
            <h4 class="d-flex align-items-center fw-bold">
                <img src="{{asset('storage/site/image/icon/gacha-black.png')}}"
                alt="ガチャサイト" class="d-block" style="height:2.4rem;">

                ガチャ
            </h4>
            <div class="row g-0">
                <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
                href="{{route('admin.gacha')}}">
                    <div class="fw-bold">公開中ガチャ</div>

                    <div class="fs-3">{{ number_format( $gacha_data['published_count'] ) }}</div>
                </a></div>

                <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
                href="{{route('admin.point_sales_report')}}">
                    <div class="fw-bold">月間売上</div>
                    <div class="fs-3">{{ number_format( $gacha_data['sales'] ) }}</div>
                </a></div>


                <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
                href="{{route('admin.shipped')}}">
                    <div class="fw-bold">発送待ち</div>

                    <div class="fs-3">{{ number_format( $gacha_data['waiting_shippeds_count'] ) }}</div>
                </a></div>

            </div>
        </section>



        <section class="card card-body border-0 rounded-4 mb-4">
            <h4 class="d-flex align-items-center fw-bold">
                <img src="{{asset('storage/site/image/icon/shop-black.png')}}"
                alt="商品ストアー" class="d-block" style=" height:2.4rem;">

                EC販売
            </h4>
            <div class="row g-0">

                <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
                href="{{route('admin.store_item')}}">
                    <div class="fw-bold">販売中アイテム</div>

                    <div class="fs-3">{{ number_format( $store_data['published_count'] ) }}</div>
                </a></div>

                <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
                href="{{route('admin.store.sales_report')}}">
                    <div class="fw-bold">月間売上</div>
                    <div class="fs-3">{{ number_format( $store_data['sales'] ) }}</div>
                </a></div>

                <div class="col-6 col-md-3"><a class="btn text-start text-secondary w-100"
                href="{{route('admin.store.shipped')}}">
                    <div class="fw-bold">発送待ち</div>

                    <div class="fs-3">{{ number_format( $store_data['waiting_shippeds_count'] ) }}</div>
                </a></div>

            </div>
        </section>

    </div>
@endsection
