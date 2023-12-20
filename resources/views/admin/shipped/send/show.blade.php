@extends('admin.layouts.app')


@section('title','発送受付・発送済み詳細')


@section('meta') @php
$active_key = 'shipped';
@endphp @endsection

@section('style')
<link href="{{ asset('css/steps.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div class="container mb-4">

        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.shipped') }}"
                >{{ '発送受付' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.shipped.send') }}">発送済み</a></li>
                <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">発送済み詳細</h2>

        <div class="mx-auto mt-5" style="max-width:900px;">

            <!-- ステップ -->
            <section class="form-steps-pill mx-auto text-secondary py-4">
                <div class="step-box text-primary">
                    <div class="step_num mb-2 bg-primary">1</div>
                    <span style="font-size: 16px;">発送申請</span>
                </div>
                <i class="bi bi-caret-right-fill mb-3 text-primary"></i>
                <div class="step-box text-primary">
                    <div class="step_num mb-2 bg-primary">2</div>
                    <span style="font-size: 16px;">発送待ち</span>
                </div>
                <i class="bi bi-caret-right-fill mb-3 text-primary"></i>
                <div class="step-box text-primary">
                    <div class="step_num mb-2 bg-primary">3</div>
                    <span style="font-size: 16px;">発送完了</span>
                </div>
            </section>

            <section class="card card-body bg-white my-4 text-center">

                <h3 class="text-success">
                    発送通知は送信済みです
                </h3>

            </section>

            <!-- お届け先と利用ポイント -->
            <section class="my-4">
                <div class="mb-2">発送コード：{{ $user_shipped->code}} </div>
                @include('shipped.common.confirm_list')
            </section>

            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{ route('admin.shipped.send') }}"
                    class="btn btn-lg btn-light border w-100"
                    >発送済み一覧に戻る</a>
                </div>
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{ route('admin.shipped.waiting') }}"
                    class="btn btn-lg btn-light border w-100"
                    >発送待ち一覧</a>
                </div>
            </section>

        </div>


    </div>
@endsection
