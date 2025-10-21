@extends('layouts.sub_toggl')

<!----- title ----->
@section('title','お知らせ')

@section('meta')
    @php
        $meta_title = 'お知らせ';
    @endphp
@endsection


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ</li>
            </ol>
        </nav>
    </div>




    <div class="container  py-md-4 mb-5">
        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            お知らせ
        </h2>


        <div class="my-5">

            <u-infomation-list
            token="{{ csrf_token() }}"
            r_api_list="{{route('infomation.api.list')}}"
            no_types_string="ec"
            ></u-infomation-list>

        </div>


    </div>
@endsection
