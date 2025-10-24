@extends('admin.layouts.app')


@section('title','EC発送受付詳細')


@section('meta') @php
$active_key = 'store_shipped';
$active_store_menu = true;
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
                <li class="breadcrumb-item"><a href="{{ route('admin.store.shipped') }}"
                >{{ 'EC発送受付' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">詳細</li>
            </ol>
        </nav>


        <h2 class="my-5 py-3 border-bottom">EC発送受付詳細</h2>


        <div class="mx-auto" style="max-width:900px;">

            <!-- ステップ -->
            @include('store.shipped.show_steps')


            <section class="card card-body bg-white my-4 text-center">

                @if($store_history->state_id>20)
                    <!--発送完了-->
                    <h3 class="text-success">発送通知は送信済みです</h3>
                @else
                    <!--未完了-->
                    <h3 class="text-warning">発送受付が届いています</h3>
                    <p>発送内容をご確認の上、商品の発送が完了しましたら『発送通知をする』ボタンを押してください。</p>
                @endif

            </section>


            <!-- 本文 -->
            @include('store.shipped.show_body')


            @if($store_history->state_id==11)
                <section class="card card-body bg-white my-4 text-center">

                    <h5 class="text-warning">
                        発送する内容にお間違いはありませんか？
                    </h5>
                    <p>
                        発送内容をご確認の上、商品の発送が完了しましたら発送通知をするボタンを押してください。
                    </p>

                    <div class="col-md-8 mx-auto my-3">
                        <form action="{{ route('admin.store.shipped.waiting.update' ) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="ids[]"    value="{{$store_history->id}}">
                            <input type="hidden" name="state_id" value="{{$store_history->state_id}}">

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
            @endif


            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <a href="{{ route('admin.store.shipped') }}"
                    class="btn btn-lg btn-light border w-100"
                    >EC発送一覧に戻る</a>
                </div>
            </section>

        </div>


        </div>


    </div>
@endsection
