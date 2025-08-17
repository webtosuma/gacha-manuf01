@extends('admin.layouts.app')


@section('title','ポイント売上レポート')


@section('meta')

    @php
    $active_key = 'point_sales_report';
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
                <li class="breadcrumb-item active" aria-current="page">ポイント売上レポート</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">ポイント売上レポート</h2>

        <a-pointsalesreport-list
        token="{{ csrf_token() }}"
        r_api_list="{{ route('admin.api.point_sales_report') }}"
        ></a-pointsalesreport-list>


    </div>
@endsection
