@extends('admin.layouts.app')


@section('title',$movie->name.'編集')


@section('meta') @php
$active_key = 'movie';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.movie') }}"
                >{{ '演出動画' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ '編集' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '『'.$movie->name.'』編集' }}</h2>

        <a href="{{route('admin.movie')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <!--演出動画名(name)-->
        <section class="py-5 border-bottom">
            <form action="{{ route('admin.movie.update',$movie) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.movie._inputs')


            </form>
        </section>


    </div>
@endsection
