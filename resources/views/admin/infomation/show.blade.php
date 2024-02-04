@extends('admin.layouts.app')


@section('title',$infomation->title)


@section('meta') @php
$active_key = 'infomation';
$active_submenu = true;
@endphp @endsection


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
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.infomation') }}"
                >{{ 'お知らせ' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ $infomation->title }}</li>
            </ol>
        </nav>

        <a href="{{route('admin.infomation.edit',$infomation)}}"
        class="btn btn-warning text-white my-3"
        >編集する</a>

        @include('footer_menu.infomation.article')


    </div>
@endsection
