@extends('admin.layouts.app')


@section('title','カテゴリー　並び替え')


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
                >{{ 'カテゴリー　並び替え' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ 'カテゴリー　並び替え' }}</h2>

        <a href="{{route('admin.category')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>

        <section class="col-md-8 mx-auto">
            <form action="{{ route('admin.category.change_order.update',) }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('patch')

                <a-category-change-order
                token="{{ csrf_token() }}"
                r_api_list="{{route('admin.api.gacha.category')}}"
                ></a-category-change-order>

                <div class="col-md-6 mt-5 mx-auto">
                    <button class="btn btn-warning btn-lg w-100">更新</button>
                </div>

            </form>
        </section>


    </div>
@endsection
