@extends('admin.layouts.app')


@section('title','サブスク管理')


@section('meta') @php
$active_key = 'subscription';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">サブスク管理</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">サブスク管理</h2>

        <a href="{{ route('admin.subscription.create') }}"
        class="btn btn-primary text-white shadow">
        <i class="bi bi-plus-lg"></i>
        {{'新規登録'}}
        </a>



        <section class="row gy-5 justify-content-center p-3">
            @foreach ($subscriptions as $subscription_id => $subscription)
                <div class="col-12 col-lg-4">

                    @include('point_sail.subscription.card')

                </div>
            @endforeach
        </section>


    </div>
@endsection
