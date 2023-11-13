@extends('admin.layouts.app')


@section('title','登録ユーザー')


@section('meta') @php
$active_key = 'user';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">登録ユーザー</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">登録ユーザー</h2>

    </div>
@endsection
