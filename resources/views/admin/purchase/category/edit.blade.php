@extends('admin.layouts.app')


@section('title',$purchase_category->name.'編集')


@section('meta') @php
$active_key = 'purchase';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.purchase') }}"
                >{{ '買取表' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.purchase.category') }}"
                >{{ 'カテゴリー' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ $purchase_category->name }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '『'.$purchase_category->name.'』編集' }}</h2>

        <a href="{{route('admin.purchase.category')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.purchase.category.update',$purchase_category) }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.purchase.category._inputs')


            </form>
        </section>

    </div>
@endsection
