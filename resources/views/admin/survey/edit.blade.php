@extends('admin.layouts.app')


@section('title', $survey->id ? '編集' : '新規登録' )


@section('meta') @php
$active_key = 'survey';
$active_submenu = true;
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


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
                >{{ $survey->id ? '編集' : '新規登録' }}</li>
            </ol>
        </nav>

        <a href="{{route('admin.survey')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <a-survey-editform
        token="{{ csrf_token() }}"
        r_api_show="{{route('admin.api.survey.show', $survey->id )}}"
        ></a-survey-editform>

        {{-- r_api_show="{{route('admin.api.survey.show', $survey??null )}}" --}}

    </div>
@endsection
