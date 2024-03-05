{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','発送待ち詳細')


@section('meta')
    @php $header_back_btn = true; @endphp
@endsection


@section('style')
<link href="{{ asset('css/steps.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shipped') }}">発送</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shipped.waiting') }}">発送待ち</a></li>
                <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>
    </div>




    <div class="container py-md-4 mb-5">
        <h3 class="d-none d-md-block mb-5">発送待ち詳細</h3>


        <div class="mx-auto" style="max-width:900px;">

            <!-- ステップ -->
            <section class="form-steps-pill mx-auto text-secondary py-3">
                <div class="step-box text-primary">
                    <div class="step_num mb-2  bg-primary">1</div>
                    <span style="font-size: 16px;">発送申請</span>
                </div>
                <i class="bi bi-caret-right-fill mb-3 text-primary"></i>
                <div class="step-box text-primary">
                    <div class="step_num mb-2  bg-primary">2</div>
                    <span style="font-size: 16px;">発送待ち</span>
                </div>
                <i class="bi bi-caret-right-fill mb-3"></i>
                <div class="step-box">
                    <div class="step_num mb-2">3</div>
                    <span style="font-size: 16px;">発送完了</span>
                </div>
            </section>


            <section class="card card-body bg-white my-4 text-center">

                <h3 class="text-warning">
                    発送準備中
                </h3>
                <p>
                    準備が整い次第、商品を発送いたします。
                </p>

            </section>

            <!-- お届け先と利用ポイント -->
            <section class="my-4">
                <div class="mb-2">発送コード：{{ $user_shipped->code}}</div>
                <div class="mb-2">申請日時：{{ $user_shipped->created_at->format('Y年m月d日 H:i') }}</div>
                {{-- <div class="mb-2">発送日時：{{ $user_shipped->shipment_at->format('Y年m月d日 H:i') }}</div> --}}

                @include('shipped.common.confirm_list')
            </section>

            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{route('shipped.waiting')}}"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >発送待ち一覧に戻る</a>
                </div>
            </section>

            <!--注意事項-->
            @include('includes.notes')

        </div>
    </div>
@endsection
