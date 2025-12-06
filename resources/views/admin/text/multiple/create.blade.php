@extends('admin.layouts.app')


@section('title',$text_type['label'].' 新規登録')


@section('meta') @php
/* 複数の文書保存　一覧 */
$active_key = 'text';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.text') }}"
                >{{ '文書設定' }}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.text.multiple',$text->type)}}"
                >{{$text_type['label']}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">新規登録</li>
            </ol>
        </nav>



        <h2 class="mt-5 py-3 border-bottom">
            <div class="fs-5">{{$text_type['label']}}</div>
            <div class="">{{'新規登録'}}</div>
        </h2>


        <div class="d-flex gap-3 my-3">

            <a href="{{route('admin.text.multiple', $text->type )}}"
            class="btn border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>戻る</a>

        </div>

        <section>
            <form action="{{ route('admin.text.multiple.store', $text->type ) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf

                @include('admin.text.multiple._inputs')


            </form>
        </section>

    </div>
@endsection
