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



    </div>
@endsection
