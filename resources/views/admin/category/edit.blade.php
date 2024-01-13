@extends('admin.layouts.app')


@section('title',$gacha_category->name.'編集')


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
                >{{ $gacha_category->name }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '『'.$gacha_category->name.'』編集' }}</h2>

        <a href="{{route('admin.category')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.category.update',$gacha_category) }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.category._inputs')


            </form>
        </section>

    </div>
@endsection
