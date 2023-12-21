@extends('admin.layouts.app')


@section('title','カテゴリー　新規登録')


@section('meta') @php
$active_key = 'category';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.category') }}"
                >{{ 'カテゴリー' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ 'カテゴリー　新規登録' }}</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">{{ 'カテゴリー　新規登録' }}</h2>


        <section>
            <form action="{{ route('admin.category.store',) }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf

                @include('admin.category._inputs')


            </form>
        </section>

    </div>
@endsection
