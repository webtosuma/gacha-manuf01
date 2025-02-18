@extends('admin.layouts.app')


@section('title','サイト背景編集')


@section('meta') @php
$active_key = 'back_ground';
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
                {{-- <li class="breadcrumb-item"><a href="{{ route('admin.back_ground') }}"
                >{{ 'サイト背景' }}</a></li> --}}
                <li class="breadcrumb-item active" aria-current="page"
                >{{ 'サイト背景編集' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ 'サイト背景編集' }}</h2>

        {{-- <a href="{{url()->previous()}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a> --}}


        <section>
            <form action="{{ route('admin.back_ground.update') }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.back_ground._inputs')


            </form>
        </section>

    </div>
@endsection
