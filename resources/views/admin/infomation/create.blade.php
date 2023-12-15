@extends('admin.layouts.app')


@section('title','お知らせ　新規登録')


@section('meta') @php
$active_key = 'infomation';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.infomation') }}"
                >{{ 'お知らせ' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ 'お知らせ　新規登録' }}</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">{{ 'お知らせ　新規登録' }}</h2>


        <section>
            <form action="{{ route('admin.infomation.store',) }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf

                @include('admin.infomation._inputs')


            </form>
        </section>

    </div>
@endsection
