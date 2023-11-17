@extends('layouts.app')

<!----- title ----->
@section('title','発送申請・確認')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user_prize') }}">取得した商品</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送申請</li>
            </ol>
        </nav>
    </div>


    <div class="container py-4 mb-5">
        <h3 class="mb-">発送申請・確認</h3>

        <form action="{{ route('shipped.appli.comp') }}" method="POST">
            @csrf
            @method('POST')

            <div class="mx-auto" style="max-width:900px;">

                <!-- お届け先と利用ポイント -->
                <section class="my-4">
                    <ul class="list-group bg-white">
                        <li class="list-group-item p-4 text-center">
                            <span class="text-primary fs-5">*内容をご確認の上、発送申請を確定させてください。</span>
                        </li>
                        <li class="list-group-item p-3">
                            <h5>お届け先住所</h5>
                        <input type="hidden" name="user_address_id" value="{{ $user_address->id }}">
                            <div class="fw-bold">
                                {{ $user_address->name }} 様
                            </div>
                            <div class="fw-bold">
                                <span>{{ '〒'.$user_address->postal_code }}</span>
                                <span>{{ $user_address->todohuken }}</span>
                                <span>{{ $user_address->shikuchoson }}</span>
                                <span>{{ $user_address->number }}</span>
                        </div>
                        </li>
                        <li class="list-group-item p-3">
                            <h5>利用ポイント</h5>
                            <div class="d-flex justify-content-between">
                                <span class="form-text">配送料・手数料：</span>
                                <span>¥0</span>
                            </div>
                            <div class="d-flex justify-content-between fs-5 fw-bold">
                                <span class="">合計利用ポイント：</span>
                                <span class="text-danger">¥0</span>
                            </div>
                        </li>
                        <li class="list-group-item p-3">
                            <h5>発送する商品</h5>

                        <div class="row p-3">
                            @foreach ($user_prizes as $user_prize)
                                <input type="hidden" name="user_prize_ids[]" value="{{ $user_prize->id }}">

                                <div class="col-3 col-md-2 p-0 pe-2">
                                    <div class="">
                                        <ratio-image-component
                                        style_class="ratio ratio-3x4 rounded-3"
                                        url="{{ $user_prize->prize->image_path }}" />
                                    </div>
                                    <h6 classs="form-text">{{ $user_prize->prize->name }}</h6>
                                </div>
                            @endforeach
                        </div>
                            <div class="text-end">
                                <span class="me-3">合計</span>
                                <span class="fs-3">{{ $user_prizes->count() }}</span>点
                            </div>
                        </li>
                    </ul>
                </section>


                <section class="my-5">
                    <div class="col-md-8 mx-auto my-3">

                        <disabled-button
                        style_class="btn btn-lg btn-primary text-white w-100"
                        btn_text="発送申請を確定する"></disabled-button>

                    </div>
                    <div class="col-md-8 mx-auto my-3">
                        <a onclick="history.back(); return false;" href="#"
                        class="btn btn-lg btn-light border w-100"
                        >お届け先を変更する</a>
                    </div>
                </section>
            </div>

        </form>
    </div>

@endsection
