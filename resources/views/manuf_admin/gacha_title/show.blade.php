@extends('manuf_admin.layouts.gacha_title')


@section('title',$gacha_title->name)


@section('meta') @php
$active_key = 'gacha_title.show';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection



@section('content')


    {{-- パンくずリスト --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
            >{{ 'Top' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$gacha_title->name}}</li>
        </ol>
    </nav>



    <div class="row mx-0 g-3" style="min-height:90vh;">


        <!--flex-c2-1 -->
        <div class="col bg-white order-2">
            <div class="mx-auto" style="max-width:600px;">


                @include('manuf_admin.gacha_title.common.title_discription')




            </div>
        </div>
        <!--flex-c2-2 -->
        <aside class="col-12 col-md-4 pe-0  order-1 order-md-2">
            <div class="position-sticky ps-2 " style="top: 0rem; ">
                <div class="p-3 bg-body rounded-4 mb-4">
                    <div class="row">
                        <div class="col">

                            <!--編集ボタン-->
                            <a href="{{route('admin.gacha_title.edit',$gacha_title)}}"
                            class="btn btn-light border"
                            ><i class="bi bi-pencil-fill me-2"></i>編集</a>

                        </div>
                        <div class="col-auto">

                            <!--削除ボタン-->
                            <a href="#"
                            class="btn btn-light border"
                            data-bs-toggle="modal"
                            data-bs-target="{{'#deleteModal'.'delete'}}"
                            ><i class="bi bi-trash"></i></a>


                        </div>
                    </div>


                </div>
                <div class="p-3 bg-body rounded-4 mb-4">


                    hoge


                </div>


            </div>
        </aside>


    </div>

    <form  action="{{route('admin.gacha_title.destroy',$gacha_title)}}" method="post">
        @csrf
        @method('DELETE')

        <delete-modal-component
        index_key="delete"
        icon="bi-trash"
        func_btn_type="submit"
        button_class="invisible">
            <div>
                <span class="fw-bold">このタイトルを削除します。
                <br />よろしいですか？
            </div>
        </delete-modal-component>
    </form>


@endsection
