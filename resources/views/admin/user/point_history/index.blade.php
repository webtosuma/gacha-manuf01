@extends('admin.layouts.app')


@section('title','ポイント履歴')


@section('meta') @php
$active_key = 'user';
@endphp @endsection


@section('style')
    <style>
        .form-check-input:checked {
            background-color: hsl(330, 63%, 59%);
            border-color: hsl(330, 63%, 59%);
        }
    </style>
@endsection



@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>

                <li class="breadcrumb-item"><a href="{{ route('admin.user') }}"
                    >{{ '登録ユーザー' }}</a></li>

                @if($user){{--個人--}}
                <li class="breadcrumb-item"><a href="{{ route('admin.user.show', $user) }}"
                    >{{ $user->name }}</a></li>
                @endif

                <li class="breadcrumb-item active" aria-current="page">ポイント履歴</li>
            </ol>
        </nav>

        @if($user)
            <a href="{{route('admin.user.show', $user)}}"
            class="btn my-3 border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>戻る</a>
        @else
            <a href="{{route('admin.user')}}"
            class="btn my-3 border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>
        @endif


        <section class="mb-5">
            <h2 class="mb-3 py-3 border-bottom">
                @if($user){{--個人--}}
                    @if ($user->admin)<span class="text-primary">●</span> @endif
                    {{ $user->name }}様
                @endif

                ポイント履歴
            </h2>

            @if($user)
                @include('admin.user.common.profile')
            @endif
        </section>



        <!-- 削除 -->
        {{-- @if( $user )
        <form action="{{route('admin.user.point_history.destroy_confirm',$user)}}" class="col-md-4" method="post">
            @csrf

            <div class="input-group mb-3">
                <span class="input-group-text">ポイント履歴ID</span>
                <input type="number" class="form-control" name="point_history_id" value="1" >
                <button class="btn btn-outline-secondary" type="submit">以降を削除</button>
            </div>
            <div class="form-text">*購入分除く</div>
        </form>
        @endif --}}


        <form action="" method="post">
            @csrf
            @method('DELETE')


            <!-- 削除ボタン・ページネーション -->
            @if( $point_histories->count() )
                <div class="d-flex justify-content-between mt-3">
                    <div class="col">
                        {{ $point_histories->links('vendor.pagination.bootstrap-4') }}
                    </div>

                    <div class="col-auto">
                        <button type="button"
                        data-bs-target="#deleteModal" data-bs-toggle="modal"
                        class="btn btn-light border text-danger  disabled"
                        >一括削除</button>
                    </div>
                </div>
            @endif


            <!-- 一覧 -->
            <ul class="list-group list-group-flush">
                @include('admin.user.point_history._types')
            </ul>


            <!-- 削除ボタン・ページネーション -->
            @if( $point_histories->count() )
                <div class="d-flex justify-content-between mt-3">
                    <div class="col">
                        {{ $point_histories->links('vendor.pagination.bootstrap-4') }}
                    </div>

                    <div class="col-auto">
                        <button type="button"
                        data-bs-target="#deleteModal" data-bs-toggle="modal"
                        class="btn btn-light border text-danger  disabled"
                        >一括削除</button>
                    </div>
                </div>
            @endif


            <!-- 削除モーダル -->
            <delete-modal-component
            index_key=""
            icon="bi-trash"
            func_btn_type="submit"
            button_class="invisible">
                <div>
                    選択したポイント履歴を
                    <br />全て削除します。
                    <br />よろしいですか？
                </div>
            </delete-modal-component>

        </form>


    </div>
@endsection
