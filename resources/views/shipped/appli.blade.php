@extends('layouts.app')

<!----- title ----->
@section('title','発送申請')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user_prize') }}">取得した景品</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送申請</li>
            </ol>
        </nav>
    </div>



    <u-shipped-parrent-form
    token="{{ csrf_token() }}"
    r_store="{{ route('api.use_address.store') }}"
    ></u-shipped-parrent-form>









    <div class="container py-4 mb-5">
        <h3 class="mb-">発送申請</h3>

        <div class="mx-auto" style="max-width:900px;">

            <!-- お届け先選択 -->
            <section class="my-4">
                <h5>お届け先の選択</h5>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" value="" checked>
                    <div class="form-check-label">
                        毎回このお届け先を使う
                    </div>
                </label>
                <ul class="list-group bg-white">
                    <li class="list-group-item">
                        <label class="d-block">
                            <div class="row g-0">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_address_id" value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">
                                        芥川　伸雄 様
                                    </div>
                                    <div class="fw-bold">
                                        113-0033
                                        東京都文京区本郷4丁目16-6
                                        天翔オフィス後楽園507
                                    </div>
                                </div>
                            </div>
                        </label>
                    </li>
                    <li class="list-group-item">
                        <label class="d-block">
                            <div class="row g-0">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_address_id" value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">
                                        芥川　伸雄 様
                                    </div>
                                    <div class="fw-bold">
                                        113-0033
                                        東京都文京区本郷4丁目16-6
                                        天翔オフィス後楽園507
                                    </div>
                                </div>
                            </div>
                        </label>
                    </li>
                    <li class="list-group-item">
                        <label class="d-block">
                            <div class="row g-0">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_address_id" value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">
                                        芥川　伸雄 様
                                    </div>
                                    <div class="fw-bold">
                                        113-0033
                                        東京都文京区本郷4丁目16-6
                                        天翔オフィス後楽園507
                                    </div>
                                </div>
                            </div>
                        </label>
                    </li>
                    <!-- お問い合わせ先追加 -->
                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary text-white"
                        >お届け先の新規登録</button>
                    </li>
                </ul>
            </section>

            <!-- 利用ポイント -->
            <section class="my-4">
                <h5>利用ポイント</h5>
                <ul class="list-group bg-white">
                    <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between">
                            <span class="form-text">配送料・手数料：</span>
                            <span>¥0</span>
                        </div>
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span class="">合計利用ポイント：</span>
                            <span class="text-danger">¥0</span>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- 発送する景品 -->
            <section class="my-4">
                <h5>発送する景品</h5>
                <ul class="list-group bg-white">
                    <li class="list-group-item">
                        <div class="row">
                            @foreach ($user_prizes as $user_prize)


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
                    </li>
                    <li class="list-group-item text-end">
                        <span class="me-3">合計</span>
                        <span class="fs-3">{{ $user_prizes->count() }}</span>点
                    </li>
                </ul>
            </section>


            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <button class="btn btn-lg btn-warning text-white w-100"
                    >発送内容を確認する</button>
                </div>
                <div class="col-md-8 mx-auto my-3">
                    <button class="btn btn-lg btn-light border w-100"
                    >発送する景品を変更する</button>
                </div>
            </section>


        </div>

    </div>
    <div class="container py-4 mb-5">
        <h3 class="mb-">発送申請・確認</h3>

        <div class="mx-auto" style="max-width:900px;">

            <!-- お届け先と利用ポイント -->
            <section class="my-4">
                <ul class="list-group bg-white">
                    <li class="list-group-item p-3">
                        <h5>お届け先住所</h5>
                        <div class="fw-bold">
                            芥川　伸雄 様
                        </div>
                        <div class="fw-bold">
                            113-0033
                            東京都文京区本郷4丁目16-6
                            天翔オフィス後楽園507
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
                    <button class="btn btn-lg btn-primary text-white w-100"
                    >発送申請を確定する</button>
                </div>
                <div class="col-md-8 mx-auto my-3">
                    <button class="btn btn-lg btn-light border w-100"
                    >お届け先を変更する</button>
                </div>
            </section>
        </div>
    </div>
    <div class="container py-4 mb-5">
        <h3 class="mb-">発送申請・確認</h3>

        <div class="mx-auto" style="max-width:900px;">
            <section class="my-4">
                <h5>景品の発送を受け付けました</h5>
                <ul class="list-group bg-white">
                    <li class="list-group-item py-4">商品発送の進捗は、『発送申請履歴』よりご確認ください。</li>
                </ul>
            </section>
        </div>

    </div>
@endsection
