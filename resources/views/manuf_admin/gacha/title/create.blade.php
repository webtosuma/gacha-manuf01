@extends('manuf_admin.layouts.app')


@section('title','タイトル新規登録')


@section('meta') @php
$active_key = 'gacha_title.show';
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
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha.title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ 'タイトル新規登録' }}</li>
        </ol>
    </nav>



    <section class="container">

        <form action="{{ route('admin.gacha.title.store', $gacha_title) }}" method="POST"
        novalidate
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf


            <div class="row mx-0 g-3" style="min-height:90vh;">


                <!--flex-c2-1 -->
                <div class="col bg-white">
                    <div class="mx-auto" style="max-width:600px;">


                        @include('manuf_admin.gacha.title._inputs')



                    </div>
                </div>
                <!--flex-c2-2 -->
                <aside class="col-12 col-md-4 pe-0  order-1 order-md-2">
                    <div class="position-sticky ps-2 " style="top: 0rem; ">


                        @include('manuf_admin.gacha.title._links')


                    </div>
                </aside>


            </div>



        </form>

    </section>


    
@endsection
