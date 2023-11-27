@extends('admin.layouts.app')


@section('title','ポイント管理')


@section('meta') @php
$active_key = 'point';
@endphp @endsection



@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ポイント管理</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">ポイント管理</h2>



    </div>
@endsection
