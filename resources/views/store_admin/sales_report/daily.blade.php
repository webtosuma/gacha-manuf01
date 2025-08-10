@extends('admin.layouts.app')


@section('title','日別売上レポート')


@section('meta')
    @php
    $active_key = 'sales_report';
    $active_report_menu = true;
    @endphp

    <!-- D3 v3 -->
    <script src="https://d3js.org/d3.v3.min.js"></script>

    <!-- NVD3 -->
    <link href="https://cdn.jsdelivr.net/npm/nvd3/build/nv.d3.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/nvd3/build/nv.d3.min.js"></script>

@endsection



@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.store.sales_report') }}"
                >{{ 'EC売上レポート' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">日別売上レポート</li>
            </ol>
        </nav>


        <h2 class="my- py-3 border-bottom">日別売上レポート</h2>

        <a href="{{route('admin.store.sales_report')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>

        <a-store-salesreport-daily
        token="{{ csrf_token() }}"
        r_api_list="{{ route('admin.api.store.sales_report.daily',$date_format) }}"
        ></a-store-salesreport-daily>


    </div>
@endsection
