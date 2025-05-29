@extends('admin.layouts.app')


@section('title','クーポン履歴')


@section('meta') @php
$active_key = 'coupon';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.coupon') }}"
                >{{ 'クーポン管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">クーポン履歴</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">クーポン履歴</h2>


        <ul class="list-group">
            @foreach ($coupons as $coupon)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col">{{$coupon->title}}</div>
                        <div class="col-auto">hoge</div>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
@endsection
