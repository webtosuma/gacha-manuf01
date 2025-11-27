@extends('admin.layouts.app')


@section('title','アクセスログ') 


@section('meta') @php
$active_key = 'access_log';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">アクセスログ</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">アクセスログ</h2>

        <a-access-log-list
        token="{{ csrf_token() }}"
        r_api_list="{{route('admin.api.access_log')}}"
        ></a-access-log-list>

    </div>
@endsection
