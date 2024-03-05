{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title',$infomation->title )

@section('meta')
    @php
        $meta_title = $infomation->title.'-お知らせ';
        $meta_description = str_replace(["\r\n", "\r", "\n"],  '', $infomation->body_text);
        $meta_description =mb_substr($meta_description, 0, 130).'...';
        $meta_image = $infomation->image_path;
    @endphp

    <!--ヘッダーの戻るボタン-->
    @php $header_back_btn = true; @endphp

@endsection



@section('style')
<style>
    #article-head {
        margin-bottom: -18px;
        font-weight: bold;
        font-feature-settings: "palt" 1;
        line-height: 2.25rem;
        letter-spacing: .04em;
    }
    .article-body{
        font-size: 1.125rem !important;
        line-height: 2.25rem;
        margin-top: 0;
        margin-bottom: 1rem;
        margin-top: 36px;
        margin-bottom: 36px;
    }
</style>
@endsection


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
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
