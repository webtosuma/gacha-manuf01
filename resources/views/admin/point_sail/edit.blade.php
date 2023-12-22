@extends('admin.layouts.app')


@section('title',$point_sail->value.'pt編集')


@section('meta') @php
$active_key = 'point_sail';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.point_sail') }}"
                >{{ '販売ポイント' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ '編集' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '『'.$point_sail->value.'pt』編集' }}</h2>

        <a href="#" onClick="history.back(); return false;"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.point_sail.update',$point_sail) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.point_sail._inputs')


            </form>
        </section>

    </div>
@endsection
