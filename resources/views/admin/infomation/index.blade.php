@extends('admin.layouts.app')


@section('title','お知らせ')


@section('meta') @php
$active_key = 'infomation';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">お知らせ</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">お知らせ</h2>

        <section class="mx-auto px-3" style="max-width:900px;">


            <a href="{{ route('admin.infomation.create') }}"
            class="btn btn-primary text-white shadow mb-3">
            <i class="bi bi-plus-lg"></i>
            {{'新規登録'}}
            </a>

            <div class="list-group list-group-flush border rounded-4 shadowww"
            style="background:rgb(255, 255, 255, .7);">
                @foreach ($infomations as $infomation)
                    <div class="list-group-item list-group-item-action border-0 pozition-relative">
                        <a href="{{ route('admin.infomation.show',$infomation) }}">
                            <div class="row mx-3 my-2">
                                <div class="col-auto">
                                    {{ $infomation->created_at->format('Y.m.d') }}
                                </div>
                                <div class="col">
                                    {{ $infomation->title }}
                                </div>
                                <div class="col-auto text-primary">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                        <div class="position-absolute top-50 start-100 translate-middle">
                            <button class="btn border bg-white rounded-circle" type="button" style="z-index:10;"
                            id="dropdownMenuButton{{ $infomation->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $infomation->id }}"  style="z-index:100;">
                                <li><a class="dropdown-item"
                                href=""
                                >編集する</a></li>
                                <li><a class="dropdown-item"
                                href="#"
                                >削除する</a></li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>


        </section>

    </div>
@endsection
