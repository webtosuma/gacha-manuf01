{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','発送詳細')


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
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shipped') }}">発送</a></li>
                <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>
    </div>




    <div class="container py-md-4 mb-5">
        <h3 class="d-none d-md-block mb-5">発送詳細</h3>


        <div class="mx-auto" style="max-width:900px;">

            <!-- ステップ -->
            @include('shipped.show_steps')


            <section class="card card-body bg-white my-4 text-center">

                @if($user_shipped->state_id>20)
                    <!--発送完了-->
                    <h3 class="text-success">商品は発送済みです</h3>
                @else
                    <!--未完了-->
                    <h3 class="text-warning">発送準備中</h3>
                    <p>準備が整い次第、商品を発送いたします。</p>
                @endif

            </section>


            <!-- お届け先と利用ポイント -->
            <section class="my-4">
                <div class="mb-2">発送コード：{{ $user_shipped->code}}</div>
                <!--購入日-->
                <div class="mb-2">{{ $user_shipped->created_at_format }}</div>
                <!--発送日-->
                <div class="mb-2">{{ $user_shipped->shipment_at_format }}</div>

                @include('shipped.common.confirm_list')
            </section>


            {{-- <div class="my-5">
                <div class="col-md-6 mx-auto my-3">
                    <a href="#" onClick="history.back(); return false;"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >戻る</a>
                </div>
            </div> --}}
            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{route('shipped',['state_id'=>$user_shipped->state_id])}}"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >発送一覧に戻る</a>
                </div>
            </section>

            <!--注意事項-->
            @include('includes.notes')

        </div>
    </div>
@endsection
