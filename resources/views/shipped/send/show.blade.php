@extends('layouts.app')

<!----- title ----->
@section('title','発送済み詳細')


@section('style')
<link href="{{ asset('css/steps.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shipped') }}">発送履歴</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shipped.send') }}">発送済み</a></li>
                <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>
    </div>




    <div class="container py-4 mb-5">
        <h3 class="mb-5">発送済み詳細</h3>


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
                    商品は発送済みです
                </h3>

            </section>

            <!-- お届け先と利用ポイント -->
            <section class="my-4">
                <div class="mb-2">発送コード：{{ $user_shipped->code}}</div>
                <div class="mb-2">申請日：{{ $user_shipped->created_at->format('Y年m月d日 H:i') }}</div>
                <div class="mb-2">発送日：{{ $user_shipped->shipment_at->format('Y年m月d日') }}</div>

                @include('shipped.common.confirm_list')
            </section>

            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{route('shipped.send')}}"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >発送済み一覧に戻る</a>
                </div>
            </section>

        </div>
    </div>
@endsection
