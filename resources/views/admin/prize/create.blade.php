@extends('admin.layouts.app')


@section('title','商品登録')


@section('meta') @php
$active_key = 'prize';
$active_submenu = true;
@endphp @endsection

<!----- script ----->
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
                <li class="breadcrumb-item"><a href="{{ route('admin.prize') }}"
                    >{{ '商品管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">商品登録</li>
                </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">商品登録</h2>


        <section>
            <form action="{{ route('admin.prize.store') }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf

                @include('admin.prize._inputs')
            </form>
        </section>

    </div>
@endsection
