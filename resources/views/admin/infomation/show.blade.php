@extends('admin.layouts.app')


@section('title',$infomation->title)


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
                <li class="breadcrumb-item"><a href="{{ route('admin.infomation') }}"
                >{{ 'お知らせ' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ $infomation->title }}</li>
            </ol>
        </nav>


        @include('footer_menu.infomation.article')


    </div>
@endsection
