@extends('admin.layouts.app')


@section('title','日別ポイント売上レポート')


@section('meta')

    @php
    $active_key = 'point_sales_report';
    $active_report_menu = true;
    @endphp

@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.point_sales_report') }}"
                >{{ 'ポイント売上レポート' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">日別売上</li>
            </ol>
        </nav>



        <h2 class="my- py-3 border-bottom">日別ポイント売上レポート</h2>

        <a href="{{route('admin.point_sales_report')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>

        <a-pointsalesreport-daily
        token="{{ csrf_token() }}"
        r_api_list="{{ route('admin.api.point_sales_report.daily',$date_format) }}"
        ></a-pointsalesreport-daily>


    </div>
@endsection
