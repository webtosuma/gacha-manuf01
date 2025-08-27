@extends('admin.layouts.app')


@section('title',$subscription->sub_label.'編集')


@section('meta') @php
$active_key = 'subscription';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
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
                <li class="breadcrumb-item"><a href="{{ route('admin.subscription') }}"
                >{{ 'サブスク管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ $subscription->sub_label.'編集' }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">{{ $subscription->sub_label.'-編集' }}</h2>

        <a href="{{route('admin.subscription')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section>
            <form action="{{ route('admin.subscription.update',$subscription) }}" method="POST"
            novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                @include('admin.subscription._inputs')


            </form>
        </section>

    </div>
@endsection
