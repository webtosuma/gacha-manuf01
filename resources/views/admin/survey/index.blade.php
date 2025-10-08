@extends('admin.layouts.app')


@section('title','アンケート登録')


@section('meta') @php
$active_key = 'survey';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">アンケート登録</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">アンケート登録</h2>

        {{-- <div class="mb-5">
            <a href="{{route('admin.survey.create')}}" >新規登録</a>
        </div>

        @foreach ($surveys as $survey)
            <div class="">
                <a href="{{$survey->r_admin_edit}}" >{{$survey->title}}</a>
                {{$survey->r_admin_edit}}
            </div>
        @endforeach --}}

        <a-survey-list
        token="{{ csrf_token() }}"
        r_api_list="{{route('admin.api.survey')}}"
        ></a-survey-list>


    </div>
@endsection
