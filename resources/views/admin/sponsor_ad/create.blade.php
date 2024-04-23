@extends('admin.layouts.app')


@section('title','広告管理　新規登録')


@section('meta') @php
$active_key = 'sponsor_ad';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.sponsor_ad') }}"
                >{{ '広告管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ '広告管理　新規登録' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '広告管理　新規登録' }}</h2>

        <a href="{{route('admin.sponsor_ad')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>

        <section>
            <form action="{{ route('admin.sponsor_ad.store',) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf

                @include('admin.sponsor_ad._inputs')

            </form>
        </section>


    </div>
@endsection
