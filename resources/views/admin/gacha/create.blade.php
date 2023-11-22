@extends('admin.layouts.app')


@section('title','ガチャ登録')


@section('meta') @php
$active_key = 'gacha';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                    >{{ 'ガチャ管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ガチャ新規登録</li>
                </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">ガチャ新規登録</h2>


        <section>
            <form action="{{ route('admin.gacha.store',) }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf

                @include('admin.gacha._inputs')


            </form>
        </section>


    </div>
@endsection
