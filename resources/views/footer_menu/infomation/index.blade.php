@extends('layouts.app')

<!----- title ----->
@section('title','お知らせ')
@section('meta')
    @php
        $meta_title = 'お知らせ';
    @endphp
@endsection


@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ</li>
            </ol>
        </nav>
    </div>



    <div class="container py-4 mb-5">
        <h3>お知らせ</h3>


        <div class="mx-auto my-5" style="max-width:900px;">

            <div class="list-group rounded-4"
            style="background:rgb(255, 255, 255, .7);">
                @forelse ($infomations as $infomation)

                    <div class="list-group-item list-group-item-action pozition-relative">
                        <a href="{{ route('infomation.show',$infomation) }}" class="text-dark">
                            <div class="d-flex align-items-center px-3">
                                <div class="col">
                                    <div class="row py-2">

                                        <div class="col-auto">
                                            {{ $infomation->created_at->format('Y.m.d') }}
                                        </div>
                                        <div class="col-12 col-md">
                                            {{ $infomation->title }}
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto text-dark">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                @empty
                    <div class="list-group-item border-0 pozition-relative">
                        <div class="">
                            * お知らせはありません
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
