@extends('admin.layouts.app')


@section('title','発送受付・発送待ち詳細')


@section('meta') @php
$active_key = 'shipped';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
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
                <li class="breadcrumb-item"><a href="{{ route('admin.shipped.waiting') }}">発送待ち</a></li>
                <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">発送待ち詳細</h2>

        <div class="mx-auto mt-5" style="max-width:900px;">

            <!-- ステップ -->
            <section class="form-steps-pill mx-auto text-secondary py-4">
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
                    発送申請が届いています
                </h3>
                <p>
                    発送内容をご確認の上、商品の発送が完了しましたら『発送通知をする』ボタンを押してください。
                </p>

            </section>

            <!-- お届け先と利用ポイント -->
            <section class="my-4">
                <div class="mb-2">発送コード：{{ $user_shipped->code}}</div>
                <div class="mb-2">申請日時：{{ $user_shipped->created_at->format('Y年m月d日 H:i') }}</div>
                {{-- <div class="mb-2">発送日時：{{ $user_shipped->shipment_at->format('Y年m月d日 H:i') }}</div> --}}
                @include('shipped.common.confirm_list')
            </section>


            <section class="card card-body bg-white my-4 text-center">

                <h5 class="text-warning">
                    発送する内容にお間違いはありませんか？
                </h5>
                <p>
                    発送内容をご確認の上、商品の発送が完了しましたら発送通知をするボタンを押してください。
                </p>

                <div class="col-md-8 mx-auto my-3">
                    <form action="{{ route('admin.shipped.waiting.update', $user_shipped ) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        {{-- <button type="button"
                        class="btn btn-lg btn-warning text-white w-100"
                        >{{ '発送通知をする' }}</button> --}}
                        <button type="button" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{'send'}}"
                        class="btn btn-lg btn-warning text-white w-100"
                        >{{ '発送通知を送る' }}</button>


                        <delete-modal-component
                        index_key="send"
                        icon="bi-send"
                        color="warning"
                        func_btn_type="submit"
                        button_class="invisible">
                            <div>
                                <h5 class="text-warning">
                                    発送する内容にお間違いはありませんか？
                                </h5>
                                <p class="form-text">
                                    発送内容に間違いがなければ、「OK」ボタンを押してください。
                                </p>
                            </div>
                        </delete-modal-component>

                    </form>
                </div>

            </section>


            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{ route('admin.shipped.waiting') }}"
                    class="btn btn-lg btn-light border w-100"
                    >発送待ち一覧に戻る</a>
                </div>
            </section>

        </div>


    </div>
@endsection
