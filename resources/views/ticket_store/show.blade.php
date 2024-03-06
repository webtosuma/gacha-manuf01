{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title',$store->prize->name)


@section('meta')

    <!--ヘッダーの戻るボタン-->
    @php $header_back_btn = true; @endphp

@endsection


@section('style')
<style>
    .ratio-3x4{ --bs-aspect-ratio: 133.3%; }
</style>
@endsection


@section('content')

    <!--ボトムメニュー-->
    @include('ticket_store.common.bottom_menu')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ticket_store') }}">チケット交換</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$store->prize->name}}</li>
            </ol>
        </nav>
    </div>

    <div class="container py-md-4 pb-5 mb-5">
        <div class="row gy-3">
            <div class="col-12 col-md-4">

                <!--image-->
                <div class="mx-auto">
                    @include('ticket_store.common.prize_image')
                </div>

            </div>
            <div class="col-12 col-md-8">
                <div class="card card-body rounded-3 border-0 shadow-sm bg-white mb-5">

                    <!--discription-->
                    @include('ticket_store.common.prize_discription')

                </div>
                <form action="{{route('ticket_store.post', $store)}}" method="POST"
                class="mb-5 col-md-10 mx-auto">
                    @csrf

                    <select class="form-select bg-white fs-3 shadow-sm mb-3">
                        @for ($num = 1; $num <= $store->count; $num++)
                            <option value="{{$num}}">{{'数量：'.$num}}</option>
                        @endfor
                    </select>


                    <button class="btn btn-lg btn-danger text-white rounded-pill shadow w-100"
                    >チケットと交換する</button>
                    {{-- <button class="btn btn-lg btn-outline-secindary border text-dark rounded-pill shadow w-100 mt-3">チケットと交換する</button> --}}

                    <a href="{{ route('ticket_store') }}"
                    class="btn btn-lg btn-light rounded-pill border w-100 mt-3"
                    >チケット交換一覧に戻る</a>

                </form>
            </div>
        </div>
    </div>
@endsection
