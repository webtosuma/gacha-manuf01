@extends('admin.layouts.app')


@section('title',$survey->title)


@section('meta') @php
$active_key = 'survey';
$active_submenu = true;
@endphp @endsection


@section('style')  @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey') }}"
                >{{ 'アンケート登録' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ $survey->title }}</li>
            </ol>
        </nav>

        <a href="{{route('admin.survey.edit',$survey)}}"
        class="btn btn-warning text-white my-3"
        >編集する</a>



    </div>
@endsection
