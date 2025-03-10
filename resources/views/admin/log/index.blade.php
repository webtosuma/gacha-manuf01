@extends('admin.layouts.app')


@section('title','操作履歴')


@section('meta') @php
$active_key = 'log';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">操作履歴</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">操作履歴</h2>


        <a-log-list
        token="{{ csrf_token() }}"
        r_api_list="{{route('api.admin.log')}}"
        use_mail="{{ config('mail.mailers.info_smtp.from_address') }}"
        is_published="0"
        show_checkbox="{{auth()->user()->admin->fobees?1:0}}"
        ></a-log-list>


    </div>
@endsection
