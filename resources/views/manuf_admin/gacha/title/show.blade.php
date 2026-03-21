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
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha.title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$gacha_title->name}}</li>
        </ol>
    </nav>



    <div class="row mx-0 g-3" style="min-height:90vh;">


        <!--flex-c2-1 -->
        <div class="col bg-white">
            <div class="mx-auto" style="max-width:600px;">


                @include('manuf_admin.gacha.title.common.title_discription')




            </div>
        </div>
        <!--flex-c2-2 -->
        <aside class="col-12 col-md-4 pe-0  order-1 order-md-2">
            <div class="position-sticky ps-2 " style="top: 0rem; ">
                <div class="p-3 bg-body rounded-4 mb-4">


                    <a href="{{route('admin.gacha.title.edit',$gacha_title)}}" class="btn btn-warning">編集</a>


                </div>
                <div class="p-3 bg-body rounded-4 mb-4">


                    <a href="{{route('admin.gacha.title.edit',$gacha_title)}}" class="btn btn-warning">編集</a>


                </div>
                <div class="p-3 rounded-4 border border-danger mb-4">


                    <a href="{{route('admin.gacha.title.edit',$gacha_title)}}" class="btn btn-danger text-white">削除</a>


                </div>

            </div>
        </aside>


    </div>



@endsection
