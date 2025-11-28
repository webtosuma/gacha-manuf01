@extends('admin.layouts.app')


@section('title','発送受付詳細')


@section('meta') @php
$active_key = 'shipped';
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
                <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">発送受付詳細</h2>

        <div class="mx-auto mt-5" style="max-width:900px;">

            <!-- ステップ -->
            @include('shipped.show_steps')


            <section class="card card-body bg-white my-4 text-center">

                @if($user_shipped->state_id>20)
                    <!--発送完了-->
                    <h3 class="text-success">発送通知は送信済みです</h3>
                @else
                    <!--未完了-->
                    <h3 class="text-warning">発送受付が届いています</h3>
                    <p>発送内容をご確認の上、商品の発送が完了しましたら『発送通知をする』ボタンを押してください。</p>
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


            @if($user_shipped->state_id==11)

                <section class="card card-body bg-white my-4 text-center">

                    <h5 class="text-warning">
                        発送する内容にお間違いはありませんか？
                    </h5>
                    <p>
                        発送内容をご確認の上、商品の発送が完了しましたら発送通知をするボタンを押してください。
                    </p>

                    <div class="col-md-8 mx-auto my-3">
                        <form action="{{ route('admin.shipped.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="ids[]"    value="{{$user_shipped->id}}">
                            <input type="hidden" name="state_id" value="{{$user_shipped->state_id}}">


                            <!--追跡コード-->
                            <div class="mb-5">
                                @include('admin.shipped._input_tracking_code')
                            </div>

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

            @else
                <!--発送コードの更新-->
                <section class="card card-body bg-white my-4 text-center">

                    <div class="col-md-8 mx-auto my-3">
                        <form action="{{ route('admin.shipped.update_trackingcode',$user_shipped) }}"
                        method="POST">
                            @csrf
                            @method('PATCH')

                            <h5 class="mb-3">追跡コードの更新</h5>

                            <!--追跡コード-->
                            <div class="mb-3">
                                @include('admin.shipped._input_tracking_code')
                            </div>


                            <button type="button" data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{'updateTrackkingCode'}}"
                            class="btn btn-lg btn-warning text-white w-100"
                            >{{ '更新' }}</button>


                            <delete-modal-component
                            index_key="updateTrackkingCode"
                            icon="bi-truck"
                            color="warning"
                            func_btn_type="submit"
                            button_class="invisible">
                                <div>
                                    <p class="form-text">
                                        追跡コードを更新します。よろしいですか？
                                    </p>
                                </div>
                            </delete-modal-component>

                        </form>
                    </div>


                </section>
            @endif


            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{route('admin.shipped',['state_id'=>$user_shipped->state_id])}}"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >発送一覧に戻る</a>
                </div>
            </section>

        </div>


    </div>
@endsection
