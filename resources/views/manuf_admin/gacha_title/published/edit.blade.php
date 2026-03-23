@extends('manuf_admin.layouts.gacha_title')


@section('title',$gacha_title->name.' 販売・公開期間 編集')


@section('meta') @php
$active_key = 'admin.gacha_title.published';
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
            <li class="breadcrumb-item active" aria-current="page">{{ '販売・公開期間 編集' }}</li>
        </ol>
    </nav>



@endsection
