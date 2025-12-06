@extends('admin.layouts.app')


@section('title',$text_type['label'].' 編集')


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
                <li class="breadcrumb-item"><a href="{{route('admin.text.multiple',$text_type['type'])}}"
                >{{$text_type['label']}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">編集</li>
            </ol>
        </nav>



        <h2 class="mt-5 py-3 border-bottom">
            <div class="fs-5">{{$text_type['label']}}</div>
            <div class="">{{'編集'}}</div>
        </h2>


        <div class="d-flex gap-3 my-3">

            <a href="{{route('admin.text.multiple', $text_type['type'] )}}"
            class="btn border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>戻る</a>

        </div>

        <section>
            @php
            $params = ['type'=>$text->type, 'text'=>$text->id];
            @endphp
            <form action="{{ route('admin.text.multiple.update', $params ) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.text.multiple._inputs')


            </form>
        </section>

    </div>
@endsection
