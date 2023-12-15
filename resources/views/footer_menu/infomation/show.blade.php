@extends('layouts.app')

<!----- title ----->
@section('title',$infomation->title )


@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('infomation') }}">お知らせ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $infomation->title }}</li>
            </ol>
        </nav>
    </div>

    @include('footer_menu.infomation.article')

@endsection
