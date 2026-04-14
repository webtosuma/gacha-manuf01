@extends('manuf_admin.layouts.gacha_title')


@section('title',$gacha_title->name.' 筐体 編集')


@section('meta') @php
$active_key = 'gacha_title.machine';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection



@section('content')


    {{-- パンくずリスト --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
            >{{ 'Top' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title.show',$gacha_title) }}"
            >{{$gacha_title->name}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title.machine',$gacha_title) }}"
            >{{ '筐体' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ '編集' }}</li>
        </ol>
    </nav>


    <h5 class="fw-bold">筐体 編集</h5>


    <form action="{{ $machine->r_admin_update }}" method="POST"
    novalidate
    enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
        @csrf
        @method('PATCH')


        <div class="row mx-0 g-0 g-md-3" style="min-height:90vh;">


            <!--flex-c2-1 -->
            <div class="col bg-white">
                <div class="mx-auto" style="max-width:600px;">


                    @include('manuf_admin.gacha_title.machine._inputs')



                </div>
            </div>
            <!--flex-c2-2 -->
            <aside class="col-12 col-md-4 pe-0  order-1 order-md-2">
                <div class="position-sticky ps-2 " style="top: 0rem; ">


                    @include('manuf_admin.gacha_title.machine._links')


                </div>
            </aside>


        </div>



    </form>


@endsection
