@extends('admin.layouts.app')


@section('title','演出動画　新規登録')


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
                >{{ '演出動画　新規登録' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '演出動画　新規登録' }}</h2>

        <a href="#" onClick="history.back(); return false;"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>

        <section>
            <form action="{{ route('admin.movie.store',) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf


                {{-- @include('admin.movie._inputs') --}}
                <div class="form-text mb-3">
                    <span class="text-danger">＊</span>入力必須
                </div>

                <div class="col-6">
                    <!--演出動画名(name)-->
                    <label class="d-block mb-4">
                        <div class="form-label">
                            演出動画名
                            <span class="text-danger">＊</span>
                        </div>

                        <input value="{{old('name', $movie->name )}}"
                        name="name"
                        type="text" class="form-control">

                        <!--error message-->
                        @if ( $errors->has('name') )
                            <div class="text-danger"> {{$errors->first('name')}} </div>
                        @endif
                    </label>
                </div>

                <div class="col-md-4 my-5">
                    @if (!$movie->id)
                    <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
                    @else
                    <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
                    @endif
                </div>



            </form>
        </section>


    </div>
@endsection
