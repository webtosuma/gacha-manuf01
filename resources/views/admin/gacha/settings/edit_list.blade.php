@extends('admin.layouts.app')


@section('title', 'ガチャ一覧設定' )


@section('meta') @php
$active_key = 'gacha';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                >{{ 'ガチャ管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{'ガチャ一覧設定'}}</li>
            </ol>
        </nav>



        <h2 class="mt-5 py-3 border-bottom">{{'ガチャ一覧設定'}}</h2>

        <a href="{{route('admin.gacha')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>



        <!-- レインボー設定 -->
        @if($rainbows)
            @include('admin.gacha.settings.form.rainbow')
        @endif


        <!-- ガチャ販売機の画像利用設定 -->
        @include('admin.gacha.settings.form.card_image')



        <!-- ガチャの読み込み中動画の利用設定 -->
        @if(false)
            @include('admin.gacha.settings.form.loading_movie')
        @endif



        <!-- その他設定 -->
        @include('admin.gacha.settings.form.other')



        
    </div>
@endsection
