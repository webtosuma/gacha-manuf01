@extends('layouts.app')

<!----- title ----->
@section('title',$infomation->title.'-お知らせ' )

@section('meta')
    @php
        $meta_title = $infomation->title.'-お知らせ';
        $meta_description = str_replace(["\r\n", "\r", "\n"],  '', $infomation->body_text);
        $meta_description =mb_substr($meta_description, 0, 130).'...';
        $meta_image = $infomation->image_path;
    @endphp
@endsection



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
