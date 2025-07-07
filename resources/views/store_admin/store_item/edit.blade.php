@extends('admin.layouts.app')

@section('title','ECストアー商品 編集')


@section('meta') @php
$active_key = 'store_item';
$active_store_menu = true;
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
                <li class="breadcrumb-item"><a href="{{ route('admin.store_item') }}"
                >{{ 'ECストアー商品 ' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{'編集'}}</li>
                </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">{{'ECストアー商品 編集'}}</h2>

        <a href="{{ route('admin.store_item') }}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.store_item.update', $store_item) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')


                @include('store_admin.store_item._inputs')


            </form>
        </section>

    </div>
@endsection
