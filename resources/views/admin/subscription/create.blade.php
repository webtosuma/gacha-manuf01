@extends('admin.layouts.app')


@section('title','サブスク管理　新規登録')


@section('meta') @php
$active_key = 'subscription';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.subscription') }}"
                >{{ 'サブスク管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ 'サブスク管理　新規登録' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ 'サブスク管理　新規登録' }}</h2>

        <a href="{{route('admin.subscription')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>

        <section>
            <form action="{{ route('admin.subscription.store',) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf

                @include('admin.subscription._inputs')


            </form>
        </section>


    </div>
@endsection
