@extends('layouts.app')

<!----- title ----->
@section('title','お知らせ')


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

            <div class="list-group list-group-flush shadow-sm rounded-4"
            style="background:rgb(255, 255, 255, .7);">
                @foreach ($infomations as $infomation)

                    <div class="list-group-item list-group-item-action border-0 pozition-relative">
                        <a href="{{ route('infomation.show',$infomation) }}" class="text-dark">
                            <div class="d-flex align-items-center px-3">
                                <div class="col">
                                    <div class="row py-2">

                                        <div class="col-auto">
                                            {{ $infomation->created_at->format('Y.m.d') }}
                                        </div>
                                        <div class="col-auto" style="width:3rem;">
                                            @if( !$infomation->is_read )
                                                <span class="badge bg-danger">未読</span>
                                            @endif
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

                @endforeach
            </div>
        </div>
    </div>
@endsection
