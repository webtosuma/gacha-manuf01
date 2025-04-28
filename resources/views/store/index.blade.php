@extends('layouts.app')

<!----- title ----->
@section('title','商品ストアー')


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
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">商品ストアー</li>
            </ol>
        </nav>
    </div>


    <section>

        <div class="container py-md-4 py-5 mb-5">

            <h3 class="">商品ストアー</h3>

            {{-- <a href="{{route('store.search')}}">検索ページ</a> --}}

            <div class="card card-body text-center d-flex align-items-center" style="height:10rem;">hoge</div>

        </div>
    </section>


    <!--カテゴリーから探す-->
    <section>
        <div class="container py-md-4 py-5 mb-5">

            <h5 class="fs-4 fw-bold">カテゴリーから探す</h5>

            <div class="row gy-4">
                @for ($i = 0; $i < 8; $i++)
                <div class="col-3">
                    <div class="card card-body">hoge</div>
                </div>
                @endfor
            </div>
        </div>
    </section>


    <!--新着商品-->
    <section>
        <div class="container py-md-4 py-5 mb-5">

            <h5 class="fs-4 fw-bold">新着商品</h5>

            <div class="row gy-4">
                @for ($i = 0; $i < 8; $i++)
                <div class="col-3">
                    <div class="card card-body">hoge</div>
                </div>
                @endfor
            </div>
        </div>
    </section>


    <!--ランキング-->
    <section>
        <div class="container py-md-4 py-5 mb-5">

            <h5 class="fs-4 fw-bold">ランキング</h5>

            <div class="row gy-4">
                @for ($i = 0; $i < 8; $i++)
                <div class="col-3">
                    <div class="card card-body">hoge</div>
                </div>
                @endfor
            </div>
        </div>
    </section>


    <!--再入荷-->
    <section>
        <div class="container py-md-4 py-5 mb-5">

            <h5 class="fs-4 fw-bold">再入荷</h5>

            <div class="row gy-4">
                @for ($i = 0; $i < 8; $i++)
                <div class="col-3">
                    <div class="card card-body">hoge</div>
                </div>
                @endfor
            </div>
        </div>
    </section>



@endsection
