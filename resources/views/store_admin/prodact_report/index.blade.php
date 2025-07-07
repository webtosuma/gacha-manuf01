@extends('admin.layouts.app')


@section('title','EC販売商品レポート')


@section('meta') @php
$active_key = 'prodact_report';
$active_report_menu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">EC販売商品レポート</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">EC販売商品レポート</h2>



    </div>
@endsection
