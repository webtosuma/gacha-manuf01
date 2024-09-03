@extends('admin.layouts.app')


@section('title','「すべて」編集')


@section('meta') @php
$active_key = 'category';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.category') }}"
                >{{ 'カテゴリー' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ '編集' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ '『すべて』編集' }}</h2>

        <a href="{{route('admin.category')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{route('admin.category.all_update')}}" method="POST"
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                <div class="form-text mb-3">
                    <span class="text-danger">＊</span>入力必須
                </div>
                <div class="row">
                    <div class="col-md-4">


                        <div class="col-8 mx-auto">
                            <!--背景画像(bg_image)-->
                            <label class="d-block mb-4">
                                <div class="form-label">背景画像</div>

                                {{-- {{$gacha_category->bg_image_path}} --}}
                                <read-image-file-component
                                img_path="{{$gacha_category->bg_image_path}}"
                                noimg_path="{{$gacha_category->noImage()}}"
                                style_class="ratio ratio-3x4 rounded-3 border"
                                name="bg_image"
                                ></read-image-file-component>

                                <!--error message-->
                                @if ( $errors->has('bg_image') )
                                    <div class="text-danger"> {{$errors->first('bg_image')}} </div>
                                @endif
                            </label>
                        </div>


                        <div class="col-md-6 my-5 mx-auto">
                            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
                        </div>

                    </div>
                </form>
        </section>

    </div>
@endsection
