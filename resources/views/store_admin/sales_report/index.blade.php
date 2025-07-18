@extends('admin.layouts.app')


@section('title','EC売上レポート')


@section('meta') @php
$active_key = 'sales_report';
$active_report_menu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">EC売上レポート</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">EC売上レポート</h2>


        {{-- <form action="">
            <input type="hidden" name="days_type" value="2025-07-01">
            <input type="hidden" name="start_day" value="2025-07-01">
            <input type="hidden" name="last_day"  value="2025-07-31">
            <button>send</button>
        </form> --}}

        <a-store-salesreport-list
        token="{{ csrf_token() }}"
        r_api_list="{{ route('admin.api.store.sales_report') }}"
        ></a-store-salesreport-list>


    </div>
@endsection
