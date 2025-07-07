@extends('admin.layouts.app')

@section('title','ECストアー商品 動画編集')


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
                <li class="breadcrumb-item active" aria-current="page">{{'動画編集'}}</li>
                </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">{{"ECストアー商品 動画編集"}}</h2>

        <a href="#"
        class="btn my-3 border rounded-pill"
        onclick="history.back()"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.store_item.movie.update', $store_item) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')


                <div class="row">
                    <div class="col-md-6">


                        <!--商品名(item_name)-->
                        <label class="d-block mb-4">
                            <div class="form-label">商品名</div>

                            <div class="p-2 rounded border bg-body">{{$store_item->item_name}}</div>
                        </label>


                        <!--Youtube動画URL(youtube_url)-->
                        {{-- <label class="d-block mb-5">
                            <div class="form-label">
                                Youtube動画URL
                            </div>
                            <div class="form-text">
                                ＊Youtube動画を指定した場合、登録中の動画は削除されます。
                            </div>

                            <input value="{{old('youtube_url', $store_item->youtube_url )}}"
                            name="youtube_url"
                            placeholder="https://www.youtube.com/shorts?v=XXXXXX"
                            type="text" class="form-control">

                            <!--error message-->
                            @if ( $errors->has('youtube_url') )
                                <div class="text-danger"> {{$errors->first('youtube_url')}} </div>
                            @endif
                        </label> --}}


                        <!--動画(movie)-->
                        <label class="d-block mb-5">
                            <div class="form-label">
                                モバイル用動画
                            </div>

                            <div class="col-md-">
                                <read-movie-file-component
                                name="movie"
                                video_path="{{ $store_item->movie_path }}"
                                ></read-movie-file-component>
                            </div>
                            <!--error message-->
                            @if ( $errors->has('movie') )
                                <div class="text-danger"> {{$errors->first('movie')}} </div>
                            @endif
                        </label>



                        {{-- <div class="alert alert-warning border-0 shadow-sm">
                            「Youtube動画URL」を選択している場合、動画を挿入することはできません。
                            「動画」を挿入する場合は、「Youtube動画URL」の入力を消し忘れないようお気をつけください。
                        </div> --}}


                        <div class="col-md-6 mx-auto">
                            @if (!$store_item->id)
                                <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
                            @else
                                <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
                            @endif
                        </div>
                    </div>

                </div>


            </form>
        </section>

    </div>
@endsection
