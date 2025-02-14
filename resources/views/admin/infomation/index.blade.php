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

        <a-infomation-list
        token="{{ csrf_token() }}"
        r_api_list="{{route('infomation.api.list')}}"
        use_mail="{{ config('mail.mailers.info_smtp.from_address') }}"
        is_published="{{$is_published}}"
        ></a-infomation-list>

    </div>
@endsection
