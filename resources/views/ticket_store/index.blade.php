{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','チケット交換')


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
            <li class="breadcrumb-item active" aria-current="page">チケット交換</li>
            </ol>
        </nav>
    </div>


    <div class="container py-md-4 mb-5">
        <h3 class="d-none d-md-block ">チケット交換</h3>

        <div class="row gx-2 gy-4">
            @forelse ($stores as $store)
                <div class="col-12 col-md-3">
                    <a href="{{route('ticket_store.show', $store)}}" class="d-block text-dark btn border-0
                    @if($store->count<1) disabled @endif">

                        <div class="row g-3">
                            <div class="col-4 col-md-12">
                                <!--image-->
                                @include('ticket_store.common.prize_image')
                            </div>

                            <div class="col" style="font-size:16px;">
                                {{-- <div class="rounded-3 bg-dark text-white p-3 mb-3"> --}}

                                    <!--discription-->
                                    @include('ticket_store.common.prize_discription')

                                {{-- </div> --}}
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-secondary">*交換できる商品はありません</div>
            @endforelse
        </div>
    </div>


@endsection
