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

                <div class="row align-items-end">
                    <div class="col-6">
                        <label class="d-block">
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
                    <div class="col">
                        <disabled-button style_class="btn btn-warning text-white shadow" btn_text="更新する"></bdisabled-button>
                    </div>
                </div>

            </form>
        </section>



        <!--PC用動画(pc_storage)-->
        {{-- <section class="py-5 border-bottom">
            <form action="{{ route('admin.movie.update',$movie) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')
                <input type="hidden" name="name" value="{{$movie->name}}">
                <input type="hidden" name="pc" value="1">


                <div class="row align-items-end">
                    <div class="col-6">
                        <label class="d-block mb-4">
                            <div class="form-label">
                                PC用動画
                                <span class="text-danger">＊</span>
                            </div>

                            <read-movie-file-component
                            name="pc_storage"
                            video_path="{{ $movie->pc }}"
                            ></read-movie-file-component>

                            <!--error message-->
                            @if ( $errors->has('pc_storage') )
                                <div class="text-danger"> {{$errors->first('pc_storage')}} </div>
                            @endif
                        </label>
                    </div>
                    <div class="col">
                        <disabled-button style_class="btn btn-warning text-white shadow" btn_text="更新する"></bdisabled-button>
                    </div>
                </div>

            </form>
        </section> --}}


        <!--モバイル用動画(mobile_storage)-->
        <section class="py-5 border-bottom">
            <form action="{{ route('admin.movie.update',$movie) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')
                <input type="hidden" name="name" value="{{$movie->name}}">
                <input type="hidden" name="mobile" value="1">

                <div class="row align-items-end">
                    <div class="col-6">
                        <label class="d-block col-6 mx-auto">
                            <div class="form-label">
                                モバイル用動画
                                <span class="text-danger">＊</span>
                            </div>

                            <read-movie-file-component
                            name="mobile_storage"
                            video_path="{{ $movie->mobile }}"
                            ></read-movie-file-component>

                            <!--error message-->
                            @if ( $errors->has('mobile_storage') )
                                <div class="text-danger"> {{$errors->first('mobile_storage')}} </div>
                            @endif
                        </label>
                    </div>
                    <div class="col">
                        <disabled-button style_class="btn btn-warning text-white shadow" btn_text="更新する"></bdisabled-button>
                    </div>
                </div>

            </form>
        </section>

    </div>
@endsection
