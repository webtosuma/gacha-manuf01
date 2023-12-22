@extends('admin.layouts.app')


@section('title','販売ポイント')


@section('meta') @php
$active_key = 'point_sail';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">販売ポイント</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">販売ポイント</h2>

        <a href="{{ route('admin.point_sail.create') }}"
        class="btn btn-primary text-white shadow">
        <i class="bi bi-plus-lg"></i>
        {{'新規登録'}}
        </a>

        <section class="card card-body bg-white my-3 overflow-auto">
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-white py-4 fs-"
                >編集するポイントを選択してください</li>


                @foreach ($point_sails as $point_sail)
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">

                        <div class="">
                            <div class="d-flex align-items-center gap-2">
                                @include('includes.point_icon')
                                <h3 class="m-0 fw-bold">
                                    <number-comma-component number="{{ $point_sail->value }}"></number-comma-component>
                                </h3>
                                <span>pt</span>
                            </div>

                            @if( $point_sail->service )
                            <div class="badge bg-danger-subtle rounded-pill fw-bold px-3">
                                <span class="text-danger fw-bold fs-6">{{ '+'.$point_sail->service }}</span>
                                <span class="text-danger fw-bold">ポイントお得！</span>
                            </div>

                            @endif
                        </div>


                        <!-- right contents -->
                        <div class="col-auto">
                            <div class="d-flex gap-2 align-items-center">
                                <!--購入ボタン-->
                                @if( $point_sail->is_published )
                                    <a class="btn btn-lg btn-warning text-white rounded-pill py-1
                                    disabled" style="width:8rem;">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <span>¥</span>
                                            <h5 class="m-0 fw-bold">
                                                <number-comma-component number="{{ $point_sail->price }}"></number-comma-component>
                                            </h5>
                                        </div>
                                    </a>
                                @else
                                    <a class="btn btn-lg btn-secondary text-white rounded-pill py-1
                                    disabled" style="width:8rem;">{{'非公開'}}</a>
                                @endif

                                <!--編集ボタン-->
                                <a href="{{ route('admin.point_sail.edit',$point_sail) }}"
                                class="btn btn-sm btn-light border "
                                ><i class="bi bi-pencil-fill"></i></a>

                                <!--削除モーダル-->
                                <form action="{{ route('admin.point_sail.destroy', $point_sail) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <delete-modal-component
                                    index_key="{{'delete'.$point_sail->id}}"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-sm btn-light border ">
                                        <div>
                                            <span class="fw-bold">『{{$point_sail->value}}pt』</span>を削除します。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>
                                </form>

                            </div>
                        </div>
                    </div></li>
                @endforeach


                <li class="list-group-item bg-white py-1 form-text text-end"
                >*価格は全て税込み価格です。</li>
            </ul>

        </section>
    </div>
@endsection
