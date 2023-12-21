@extends('admin.layouts.app')


@section('title','編集')


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
                <li class="breadcrumb-item active" aria-current="page">編集</li>
                </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">編集</h2>

        <a href="#" onClick="history.back(); return false;"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.prize.update', $prize) }}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')


                @include('admin.prize._inputs')


            </form>
        </section>

    </div>
@endsection
