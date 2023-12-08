@extends('admin.layouts.app')


@section('title','ガチャ公開設定')


@section('meta') @php
$active_key = 'gacha';
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
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha.show',$gacha) }}"
                >{{ $gacha->name }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">公開設定</li>
                </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">ガチャ編集</h2>


        <!--タブメニュ-->
        @php $tab='admin.gacha.published'; @endphp
        @include('admin.gacha.common.tab')


    </div>
@endsection
