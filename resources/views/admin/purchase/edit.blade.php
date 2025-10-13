@extends('admin.layouts.app')


@section('title',$purchase->title.'編集')


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
                >{{ 'クーポン管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $purchase->title.'編集' }}</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">編集</h2>

        <a href="{{route('admin.purchase')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.purchase.update',$purchase) }}" method="POST" novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.purchase._inputs')


            </form>
        </section>


    </div>
@endsection
