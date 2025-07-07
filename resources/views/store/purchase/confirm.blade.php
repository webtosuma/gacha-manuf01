@extends('store.layouts.app')

<!----- title ----->
@section('title','注文内容の確認')


<!----- script ----->
@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')


    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.keep') }}">買い物カート</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'注文内容の確認'}}</li>
            </ol>
        </nav>
    </div>
    <div class="container py-md-4 pb-5 mb-5 ">


        <form action="{{ route('store.purchase.stripe.checkout',$store_history) }}" method="POST"
        novalidate
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">

            @csrf

            {{-- <u-shipped-form
            token="{{ csrf_token() }}"
            r_index="{{ route('api.use_address') }}"
            r_store="{{ route('api.use_address.store') }}"
            r_destroy="{{ route('api.use_address.destroy') }}"

            u_prize_ids="{{ implode(',',[1]) }}"
            shipped_point="{{0}}"
            r_find="{{ route('api.user_prize.find') }}"
            ></u-shipped-form>
            --}}


            <button class="btn btn-warning">お支払いに進む</button>
        </form>
    </div>

@endsection
